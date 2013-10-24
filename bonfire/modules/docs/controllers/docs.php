<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Docs extends Base_Controller {

    protected $docsDir = 'docs';
    protected $docsExt = '.md';
    protected $docsTypeApp  = 'application';
    protected $docsTypeBf   = 'bonfire';
    protected $docsTypeMod  = 'module';

    //--------------------------------------------------------------------

    public function __construct()
    {
        parent::__construct();

        $this->load->library('template');
        $this->load->library('assets');

        $this->load->config('docs');
        $this->lang->load('docs');

        Template::set_theme(config_item('docs.theme'), 'docs');
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
        $data['sidebar'] = $this->build_sidebar();
        $data['content'] = $this->read_page($this->uri->segment_array());

        Template::set($data);
        Template::render();
    }

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
        $data['app_docs'] = $this->get_folder_files(APPPATH . $this->docsDir, $this->docsTypeApp);

        // Bonfire Specific Docs?
        $data['bf_docs'] = $this->get_folder_files(BFPATH . $this->docsDir, $this->docsTypeBf);

        // Modules with Docs?
        $data['module_docs'] = $this->get_module_docs();

        $data['docsDir'] = $this->docsDir;
        $data['docsExt'] = $this->docsExt;

        return $this->load->view('_sidebar', $data, true);
    }

    //--------------------------------------------------------------------

    /**
     * Retrieves the list of files in a folder and preps the name and
     * filename so that it's ready for creating the HTML.
     *
     * @param  String $folder The path to the folder to retrieve
     * @param  String $type   The type of documentation being retrieved
     *                        ('application', 'bonfire', or the name of the module)
     *
     * @return Array  An associative array @see parse_ini_file for format details
     */
    private function get_folder_files($folder, $type)
    {
        $tocFile = '/_toc.ini';
        $toc = array();

        if (is_dir($folder)) {

            // If a file called _toc.ini file exists in the folder,
            // we'll skip that and use it to build the links from.
            if (is_file($folder . $tocFile)) {
                $toc = parse_ini_file($folder . $tocFile, true);
            }
            // If no toc file exists, build it from the files themselves.
            else {
                $map = directory_map($folder);

                if ( ! is_array($map)) {
                    return array();
                }

                foreach ($map as $file) {
                    if (strpos($file, 'index') === false) {
                        $title = str_replace($this->docsExt, '', $file);
                        $title = str_replace('_', ' ', $title);
                        $title = ucwords($title);

                        $toc[strtolower($type) . '/' . $file] = $title;
                    }
                }
            }
        }

        return $toc;
    }

    //--------------------------------------------------------------------

    /**
     * Checks all modules to see if they include docs and prepares their
     * doc information for use in the sidebar.
     */
    private function get_module_docs()
    {
        $docs_modules = array();

        foreach (module_list() as $module) {
            $path = module_path($module) . '/' . $this->docsDir;
            if (is_dir($path)) {
                $docs_modules[$module] = $this->get_folder_files($path, $module);
            }
        }

        return $docs_modules;
    }

    //--------------------------------------------------------------------


    /**
     * Does the actual work of reading in and parsing the help file.
     *
     * @param  array  $segments The uri_segments array.
     */
    private function read_page($segments=array())
    {
        $defaultType = $this->docsTypeApp;

        // Strip the 'controller name
        if ($segments[1] == $this->router->fetch_class()) {
            array_shift($segments);
        }

        // Is this core, app, or module?
        $type = array_shift($segments);
        if (empty($type)) {
            $type = $defaultType;
        }

        // Is it a module?
        if ($type != $this->docsTypeApp && $type != $this->docsTypeBf) {
            $modules = module_list();
            if (in_array($type, $modules)) {
                $module = $type;
                $type = $this->docsTypeMod;
            } else {
                $type = $defaultType;
            }
        }

        // for now, assume we are using Markdown files as the only
        // allowed format. With an extension of '.md'
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

        switch ($type) {
            case $this->docsTypeBf:
                $content = is_file(BFPATH . $this->docsDir . '/' . $file) ? file_get_contents(BFPATH . $this->docsDir . '/' . $file) : '';
                break;

            case $this->docsTypeApp:
                $content = is_file(APPPATH . $this->docsDir . '/' . $file) ? file_get_contents(APPPATH . $this->docsDir . '/' . $file) : '';
                break;

            case $this->docsTypeMod:
                // Assume it's a module
                $mod_path = module_path($module, $this->docsDir);
                $content = is_file($mod_path . '/' . $file) ? file_get_contents($mod_path . '/' . $file) : '';
                break;
        }

        // Parse the file
        $this->load->helper('markdown_extended');
        $content = MarkdownExtended($content);

        return trim($content);
    }

    //--------------------------------------------------------------------

}