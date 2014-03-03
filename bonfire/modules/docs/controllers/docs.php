<?php defined('BASEPATH') || exit('No direct script access allowed');

class Docs extends Base_Controller
{
    protected $docsDir = 'docs';
    protected $docsExt = '.md';
    protected $docsTypeApp  = 'application';
    protected $docsTypeBf   = 'developer';
    protected $docsTypeMod  = 'module';

    protected $docsGroup    = null;

    protected $ignoreFiles  = array('_404.md');

    //--------------------------------------------------------------------

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->library('template');
        $this->load->library('assets');

        $this->load->config('docs');
        $this->lang->load('docs');

        $this->docsGroup = $this->uri->segment(2);

        // Was a doc group provided?
        if ( ! $this->docsGroup) {
            redirect('docs/' . config_item('docs.default_group'));
        }

        // Make sure we can still get to the search method.
        if ($this->docsGroup == 'search') {
           $this->docsGroup = false;
        }
        // Are we allowed to show developer docs in this environment?
        elseif ($this->docsGroup == 'developer'
                && ! config_item('docs.show_dev_docs')
                && ENVIRONMENT != 'development'
               ) {
            if (config_item('docs.show_app_docs')) {
                Template::set_message('Unable to load developer documentation.', 'warning');

                redirect('docs/application');
            }

            show_error('Unable to load the docs you requested.');
        }

        Template::set_theme(config_item('docs.theme'), 'docs');

        $this->load->helper('form');
    }

    //--------------------------------------------------------------------

    /**
     * Display the list of documents available and the current document
     *
     * @return void
     */
    public function index()
    {
        $data = array();

        $content = $this->read_page($this->uri->segment_array());
        $content = $this->post_process($content);

        $data['sidebar'] = $this->build_sidebar();
        $data['content'] = $content;

        Template::set($data);
        Template::render();
    }

    /**
     * Display search results and handles the search itself.
     *
     * @return void
     */
    public function search()
    {
        $this->benchmark->mark('search_start');

        $this->load->library('docs/docsearch');

        $terms = $this->input->post('search_terms');
        if ($terms) {
            $search_folders = array();

            if (config_item('docs.show_app_docs')) {
                $search_folders[] = APPPATH . $this->docsDir;
            }

            if (config_item('docs.show_dev_docs')) {
                $search_folders[] = BFPATH . $this->docsDir;
            }

            $results = $this->docsearch->search($terms, $search_folders);
            Template::set('results', $results);
        }

        $this->benchmark->mark('search_end');
        Template::set('search_time', $this->benchmark->elapsed_time('search_start', 'search_end'));

        Template::set('search_terms', $terms);
        Template::render();
    }

    //--------------------------------------------------------------------
    // Private Methods
    //--------------------------------------------------------------------

    /**
     * Builds a TOC for the sidebar out of files found in the following folders:
     *
     *      - application/docs
     *      - bonfire/docs
     *      - {module}/docs
     *
     * @return string The rendered HTML.
     */
    private function build_sidebar()
    {
        $data = array();
        $this->load->helper('directory');
        $this->load->helper('file');

        // Application Specific Docs?
        if ($this->docsGroup == 'application') {
            $data['docs'] = $this->get_folder_files(APPPATH . $this->docsDir, $this->docsTypeApp);
        }
        // Developer Specific Docs?
        elseif ($this->docsGroup == 'developer') {
            $data['docs'] = $this->get_folder_files(BFPATH . $this->docsDir, $this->docsTypeBf);
        }

        // Modules with Docs?
        $data['module_docs'] = $this->get_module_docs();
        $data['docsDir'] = $this->docsDir;
        $data['docsExt'] = $this->docsExt;

        $sidebar = $this->load->view('docs/_sidebar', $data, true);

        return $this->post_process($sidebar);
    }

    /**
     * Retrieves the list of files in a folder and preps the name and
     * filename so that it's ready for creating the HTML.
     *
     * @param  String $folder The path to the folder to retrieve
     * @param  String $type   The type of documentation being retrieved
     *                        ('application', 'bonfire', or the name of the module)
     * @param  Array  $ignoredFolders   A list of sub-folders we should ignore.
     *
     * @return Array  An associative array @see parse_ini_file for format details
     */
    private function get_folder_files($folder, $type, $ignoredFolders = array())
    {
        $tocFile = '/_toc.ini';
        $toc = array();

        if (is_dir($folder)) {
            // If a file called _toc.ini file exists in the folder, use it to
            // build the links.
            if (is_file($folder . $tocFile)) {
                $toc = parse_ini_file($folder . $tocFile, true);
            }
            // If no toc file exists, build it from the files themselves.
            else {
                $map = directory_map($folder);
                if ( ! is_array($map)) {
                    return array();
                }

                foreach ($map as $new_folder => $files) {
                    // Is this a folder that should be ignored?
                    if (is_string($new_folder) && in_array($new_folder, $ignoredFolders)) {
                        continue;
                    }

                    // If $files isn't an array, then make it one so that all
                    // situations may be dealt with cleanly.
                    if ( ! is_array($files)) {
                        $files = array($files);
                    }

                    foreach ($files as $file) {
                        if (in_array($file, $this->ignoreFiles)) {
                            continue;
                        }

                        // The file isn't the index, so build out a title
                        if (strpos($file, 'index') === false) {
                            $title = str_replace($this->docsExt, '', $file);
                            $title = str_replace('_', ' ', $title);
                            $title = ucwords($title);

                            $toc[$this->docsGroup . '/' . strtolower($type) . '/' . $file] = $title;
                        } else {
                            $toc[$this->docsGroup . '/' . strtolower($type)] = $type;
                        }
                    }
                }
            }
        }

        return $toc;
    }

    //--------------------------------------------------------------------

    /**
     * Checks all modules to see if they include docs and prepares their doc
     * information for use in the sidebar.
     *
     * @return array
     */
    private function get_module_docs()
    {
        $docs_modules = array();
        foreach (Modules::list_modules() as $module) {
            $ignored_folders = array();
            $path = Modules::path($module) . $this->docsDir;

            // If these are developer docs, add the folder to the path.
            if ($this->docsGroup == $this->docsTypeBf) {
                $path .= '/' . $this->docsTypeBf;
            }
            // For Application docs, ignore the 'developers' folder.
            else {
                $ignored_folders[] = $this->docsTypeBf;
            }

            if (is_dir($path)) {
                $files = $this->get_folder_files($path, $module, $ignored_folders);
                if (is_array($files) && count($files)) {
                    $docs_modules[$module] = $files;
                }
            }
        }

        return $docs_modules;
    }

    /**
     * Does the actual work of reading in and parsing the help file.
     *
     * @param  array  $segments The uri_segments array.
     *
     * @return string
     */
    private function read_page($segments = array())
    {
        $content = null;
        $defaultType = $this->docsTypeApp;

        // Strip the controller name
        if ($segments[1] == $this->router->fetch_class()) {
            array_shift($segments);
        }

        // Is this core, app, or module?
        $type = array_shift($segments);
        if (empty($type)) {
            $type = $defaultType;
        }

        // For now, assume Markdown files are the only allowed format, with an
        // extension of '.md'
        if (count($segments)) {
            $file = implode('/', $segments) . $this->docsExt;
        } else {
            $file = 'index' . $this->docsExt;
            if ($type != $this->docsTypeMod
                && ! is_file(APPPATH . $this->docsDir . '/' . $file)
               ) {
                $type = $this->docsTypeBf;
            }
        }

        // First try to load from Activities or Bonfire.
        switch ($type) {
            case $this->docsTypeBf:
                $content = is_file(BFPATH . $this->docsDir . '/' . $file) ? file_get_contents(BFPATH . $this->docsDir . '/' . $file) : '';
                break;

            case $this->docsTypeApp:
                $content = is_file(APPPATH . $this->docsDir . '/' . $file) ? file_get_contents(APPPATH . $this->docsDir . '/' . $file) : '';
                break;
        }

        // If the file wasn't found, try to find a module with the content.
        if (empty($content)) {
            $module = array_shift($segments);

            // If anything's left on $segments, it's probably a filename
            $fileName = count($segments) ? array_shift($segments) : 'index';
            $fileName .= '.md';

            // Developer docs for modules should be found under the
            // '{module}/docs/developer' path.
            $addPath = $type == $this->docsTypeBf ? '/' . $this->docsTypeBf . '/' : '/';

            // This time, try it based on the name of the segment brought in
            list($full_path, $file) = Modules::find($fileName, $module, $this->docsDir . $addPath);
            if ($full_path) {
                $content = file_get_contents($full_path . $file);
            }
        }

        // If the content is still empty, load the application/docs/404 file
        // so that we have a customizable not found file.
        if (empty($content)) {
            $content = is_file(APPPATH . $this->docsDir  . '/_404.md') ? file_get_contents(APPPATH . $this->docsDir . '/_404.md') : '';
        }

        // Parse the file
        $this->load->helper('markdown_extended');
        $content = MarkdownExtended($content);

        return trim($content);
    }

    /**
     * Perform a few housekeeping tasks on a page, like rewriting URLs to full
     * URLs, not relative, ensuring they link correctly, etc.
     *
     * @param $content
     *
     * @return string   The post-processed HTML.
     */
    private function post_process($content)
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><div>' . $content . '</div>');

        /*
         * Rewrite the URLs
         */
        foreach ($xml->xpath('//a') as $link) {
            // Grab the href value.
            $href = $link->attributes()->href;

            // If the href is null, it's probably a named anchor with no content.
            if ( ! $href) {
                // Make sure it has an href, else the XML will not close this
                // tag correctly.
                $link['href'] = ' ';

                // A title is needed so the XML will be built correctly.
                $link->title = '';

                continue;
            }

            // If the href starts with #, then attach the current_url to it
            if ($href != '' && substr_compare($href, '#', 0, 1) === 0) {
                $link['href'] = current_url() . $href;

                continue;
            }

            // If it's a full local path, get rid of it.
            if (strpos($href, site_url()) === 0) {
                $href = str_replace(site_url() . '/', '', $href);
            }

            // Strip out some unnecessary items, just in case they're there.
            if (substr($href, 0, strlen('docs/')) == 'docs/') {
                $href = substr($href, strlen('docs/'));
            }

            // This includes 'bonfire/' if it was missed during the conversion.
            if (substr($href, 0, strlen('bonfire/')) == 'bonfire/') {
                $href = substr($href, strlen('bonfire/'));
            }

            // If another 'group' is not already defined at the head of the link
            // then add the current group to it.
            if (strpos($href, $this->docsTypeApp) !== 0 &&
                strpos($href, $this->docsTypeBf)  !== 0 &&
                strpos($href, 'http')             !== 0
               ) {
                $href = $this->docsGroup . '/' . $href;
            }

            // Convert to full site_url
            if (strpos($href, 'http') !== 0) {
                $href = site_url('docs/'. $href);
            }

            // Save the corrected href
            $link['href'] = $href;
        }

        $content = $xml->asXML();
        $content = trim(str_replace('<?xml version="1.0" standalone="yes"?>', '', $content));

        // Clean up and style the tables
        $content = str_replace('<table>', '<table class="table table-hover">', $content);

        return $content;
    }
}