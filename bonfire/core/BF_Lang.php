<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BF_Lang extends CI_Lang
{
    /**
     * @var String The fallback language used for un-translated lines.
     * If you change this, you should ensure that all language files have been
     * translated to the language indicated by the new value.
     */
    protected $fallback = 'english';

    /**
     * Load a language file
     *
     * Bonfire modifies this to attempt to find language files within modules, also.
     *
     * @access  public
     * @param   mixed   $langfile   the name of the language file to be loaded. Can be an array
     * @param   string  $idiom      the language (english, etc.)
     * @param   bool    $return     return loaded array of translations?
     * @param   bool    $add_suffix add suffix to $langfile
     * @param   string  $alt_path   alternative path to look for language file
     *
     ************
     * The $module parameter has been deprecated (since 0.7.1)
     * @param   string  $module     the name of the module in which the language file may be located
     *
     * @return  mixed
     */
    public function load($langfile = '', $idiom = '', $return = false, $add_suffix = true, $alt_path = '', $module='')
    {
        $orig_langfile = $langfile;

		if (is_array($langfile))
        {
			foreach($langfile as $_lang)
            {
                $this->load($_lang);
            }

			return $return ? $this->language : true;
        }

        // Clean up the language file name
        $langfile = str_replace('.php', '', $langfile);

        if ($add_suffix == true)
        {
            $langfile = str_replace('_lang', '', $langfile).'_lang';
        }

        $langfile .= '.php';

        // If the file has already been loaded, get out of here.
        if (in_array($langfile, $this->is_loaded, true)) {
            return;
        }

        // Is there a possible module?
        $matches = explode('/', $langfile);
        $module = '';

        if (strpos($matches[0], '.php') === false)
        {
            $module = $matches[0];
            $langfile = str_replace($module . '/', '', $langfile);
        }

        unset($matches);

        $config =& get_config();
        $ci =& get_instance();

        // Check the session to see if we have a language var stored there.
        // This is done by the auth library and the Base_Controller.
        if (empty($idiom) && class_exists('CI_Session') && isset($ci->session))
        {
            if ( ! $idiom = $ci->session->userdata('language'))
            {
                $idiom = '';
            }
        }

        // Choose a default language.
        // Config file version gets the override.
        // Otherwise default to English (set as fallback above)
        if ($idiom == '')
        {
            $default_lang   = isset($config['language']) ? $config['language'] : $this->fallback;
            $idiom          = ($default_lang == '') ? $this->fallback : $default_lang;
        }

        $lang = array();
        if ($idiom != $this->fallback)
        {
            $lang = $this->load($orig_langfile, $this->fallback, true, $add_suffix, $alt_path, $module);
        }

        // Determine where the language file is and load it
        $langfilePath = "language/{$idiom}/{$langfile}";

        if ($alt_path != '' && file_exists($alt_path . $langfilePath))
        {
            include($alt_path . $langfilePath);
        }
        else
        {
            $found = false;
            $ci =& get_instance();

            if ($module != '') {
                $ci->load->add_module($module);
            }

            foreach ($ci->load->get_package_paths(true) as $package_path)
            {
                if (file_exists($package_path . $langfilePath))
                {
                    include($package_path . $langfilePath);
                    $found = true;
                    break;
                }
            }

            // Check whether $lang is empty, as the fallback may have been loaded
            if ($found !== true && empty($lang))
            {
                show_error("Unable to load the requested language file: {$langfilePath}");
            }
        }


        if (empty($lang))
        {
            log_message('error', "Language file contains no data: {$langfilePath}");
            return;
        }

        if ($return == true)
        {
            return $lang;
        }

        $this->is_loaded[] = $langfile;
        $this->language = array_merge($this->language, $lang);
        unset($lang);

        log_message('debug', "Language file loaded: {$langfilePath} ({$idiom})");
        return true;
    }

    //--------------------------------------------------------------------

}