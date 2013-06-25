<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Docs extends Base_Controller {

    //--------------------------------------------------------------------

    public function __construct()
    {
        parent::__construct();

        $this->load->library('template');
        $this->load->library('assets');
        Template::set_theme('docs', 'junk');

        $this->load->config('docs');


    }

    //--------------------------------------------------------------------


    public function index()
    {
        $data = array();

        $data['sidebar'] = $this->build_sidebar();
        $data['content'] = $this->read_page( $this->uri->segment_array() );

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
        $data['app_docs'] = $this->get_folder_files(APPPATH .'docs', 'application');

        // Bonfire Specific Docs?
        $data['bf_docs'] = $this->get_folder_files(BFPATH .'docs', 'bonfire');

        // Modules with Docs?
        $data['module_docs'] = $this->get_module_docs();

        return $this->load->view('_sidebar', $data, true);
    }

    //--------------------------------------------------------------------

    /**
     * Retrieves the list of files in a folder and preps the name and
     * filename so that it's ready for creating the HTML.
     *
     * @param  [type] $folder [description]
     * @return [type]         [description]
     */
    private function get_folder_files($folder, $type)
    {
        $toc = array();

        if (is_dir($folder))
        {
            // If a file called _toc.ini file exists in the folder,
            // we'll skip that and use it to build the links from.
            if (is_file($folder .'/_toc.ini'))
            {
                $toc = parse_ini_file($folder .'/_toc.ini', true);
            }

            // If no toc file exists, build it from the
            // files themselves.
            else
            {
                $map = directory_map($folder);

                if (!is_array($map)) return array();

                foreach ($map as $file)
                {
                    if (strpos($file, 'index') === false)
                    {
                        $title = str_replace('.md', '', $file);
                        $title = str_replace('_', ' ', $title);
                        $title = ucwords($title);

                        $toc[ strtolower($type) .'/'. $file ] = $title;
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

        foreach (module_list() as $module)
        {
            $path = module_path($module) .'/docs';
            if (is_dir($path))
            {
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
        // Strip the 'controller name
        if ($segments[1] == $this->router->fetch_class())
        {
            array_shift($segments);
        }

        // Is this core, app, or module?
        $type = array_shift($segments);

        if (empty($type)) $type = 'application';

        // Is it a module?
        if ($type != 'application' && $type != 'bonfire')
        {
            $modules = module_list();
            if (in_array($type, $modules))
            {
                $module = $type;
                $type = 'module';
            }
        }

        // for now, assume we are using Markdown files as the only
        // allowed format. With an extension of '.md'
        if (count($segments))
        {
            $file = implode('/', $segments) . '.md';
        }
        else
        {
            $file = 'index.md';

            if ($type != 'module' && !is_file(APPPATH .'docs/'. $file))
            {
                $type = 'bonfire';
            }
        }

        switch ($type)
        {
            case 'bonfire':
                $content = is_file(BFPATH .'docs/'. $file) ? file_get_contents(BFPATH .'docs/'. $file) : '';
                break;
            case 'application':
                $content = is_file(APPPATH .'docs/'. $file) ? file_get_contents(APPPATH .'docs/'. $file) : '';
                break;
            case 'module':
                // Assume it's a module
                $mod_path = module_path($module, 'docs');
                $content = is_file($mod_path .'/'. $file) ? file_get_contents($mod_path .'/'. $file) : '';
                break;
        }

        // Parse the file
        $this->load->helper('markdown');
        $content = Markdown($content);

        return trim($content);
    }

    //--------------------------------------------------------------------

}