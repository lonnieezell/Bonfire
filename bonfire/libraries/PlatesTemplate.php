<?php

namespace Bonfire;

use League\Plates\Template;
use League\Plates\Engine;

class PlatesTemplate implements TemplateInterface {

    protected $engine;

    protected $template;

    protected $theme = '';

    protected $layout = 'index';

    protected $view = '';

    protected $ci;

    //--------------------------------------------------------------------

    /**
     * Sets up our paths, gets our template and engine instances in place.
     */
    public function __construct ()
    {
        $this->ci =& get_instance();

        $this->engine = new Engine();

        $paths = config_item('template.theme_paths');

        foreach ($paths as $key => $path)
        {
            $this->engine->addFolder($key, $path);
        }

        $this->template = new Template($this->engine);

        $this->theme = config_item('template.default_theme');
    }

    //--------------------------------------------------------------------

    /**
     * The main entryway into rendering a view. This is called from the
     * controller and is generally the last method called.
     *
     * @param string $layout If provided, will override the default layout.
     */
    public function render ($layout = NULL)
    {
        // Use the default layout (index) if nothing is specified.
        $layout = ! empty($layout) ? $layout : $this->layout;
        $this->template->layout($this->theme .'::'. $layout);

        // Give us some helper paths in the views...
        $this->template->tpl_theme = $this->theme;

        // Determine which view to show.
        $view = ! empty($this->view) ? $this->view :
            $this->ci->router->fetch_class() .'/'. $this->ci->router->fetch_method();

        // Make sure the engine can find our views folder...
        $this->engine->setDirectory(APPPATH .'views');

        // Render the output!
        $output = $this->template->render($view);
        $this->ci->output->set_output($output);
    }

    //--------------------------------------------------------------------

    /**
     * Used within the template layout file to render the current content.
     * This content is typically used to display the current view.
     */
    public function content ()
    {
        // Not needed for this template engine. It provides it's own call.
    }

    //--------------------------------------------------------------------

    /**
     * Sets the active theme to use. This theme should match one of the
     * keys set in the 'theme_paths' array.
     *
     * Example:
     *      $config['template.theme_paths'] = [
     *          'admin' => '../themes/admin'
     *      ];
     *
     *      $this->template->setTheme('admin');
     *
     * @param $theme
     */
    public function setTheme ($theme)
    {
        $this->theme = $theme;
    }

    //--------------------------------------------------------------------

    /**
     * Sets the current view file to render.
     *
     * @param $file
     * @return mixed
     */
    public function setView ($file)
    {
        $this->view = $file;
    }

    //--------------------------------------------------------------------

    /**
     * Stores one or more pieces of data to be passed to the views when
     * they are rendered out.
     *
     * If both $key and $value are ! empty, then it will treat it as a
     * key/value pair. If $key is an array of key/value pairs, then $value
     * is ignored and each element of the array are made available to the
     * view as if it was a single $key/$value pair.
     *
     * @param string|array $key
     * @param mixed        $value
     */
    public function set ($key, $value = NULL)
    {
        if (is_array($key))
        {
            foreach ($key as $k => $v)
            {
                $this->template->$k = $v;
            }

            return;
        }

        $this->template->$key = $value;
    }

    //--------------------------------------------------------------------

    /**
     * Returns a value that has been previously set().
     *
     * @param $key
     * @return mixed
     */
    public function get ($key)
    {
        return isset($this->template->$key) ? $this->template->$key : null;
    }

    //--------------------------------------------------------------------

    /**
     * Determines whether or not the view should be parsed with the
     * CodeIgniter's parser.
     *
     * @param bool $parse
     * @return mixed
     */
    public function parseViews ($parse = FALSE)
    {

    }

    //--------------------------------------------------------------------

    /**
     * Theme paths allow you to have multiple locations for themes to be
     * stored. This might be used for separating themes for different sub-
     * applications, or a core theme and user-submitted themes.
     *
     * @param $path A new path where themes can be found.
     */
    public function addThemePath ($path)
    {

    }

    //--------------------------------------------------------------------

    /**
     * Removes a single theme path.
     *
     * @param $path
     */
    public function removeThemePath ($path)
    {

    }

    //--------------------------------------------------------------------

}