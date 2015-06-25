<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2013, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Bonfire Language Class
 *
 * This class replaces both CI_Lang and MX_Lang.
 *
 * It will fall back to english for un-translated lines.
 *
 * It has to extend MX_Lang, because otherwise MX will replace it
 * (see MX/Ci.php and MX/Base.php).
 */
class BF_Lang extends MX_Lang
{
    /**
     * @var String The fallback language used for un-translated lines.
     * If you change this, you should ensure that all language files have been
     * translated to the language indicated by the new value.
     */
    protected $fallback = 'english';

    public function __construct()
    {
        log_message('debug', "Bonfire MY_Lang: Language Class Initialized");
    }

    /**
     * Load a language file
     *
     * This version always loads english first (as a fallback).
     * It will tolerate either file being missing (but not both).
     *
     * Parameters not documented here are listed by CI and MX Lang.
     * Compatibility with these parameters is provided, though
     * not all of the bugs have been implemented.  For full
     * details of bug compatibility, please read our code comments.
     *
     * The langfile parameter should not be considered optional (!)
     *
     * @param   string  the name of the language file to be loaded
     * @param   string  the language (english, etc.)
     * @return  void
     */
    public function load($langfile = '', $idiom = '', $return = false, $add_suffix = true, $alt_path = '', $module = '')
    {
        if (is_array($langfile)) {
            foreach ($langfile as $_lang) {
                $this->load($_lang);
            }

            return $return ? $this->language : true;
        }

        // This check ignores $idiom, matching CI behaviour
        // (though we don't do the buggy add_suffix dance at this point).
        if (in_array($langfile . '_lang.php', $this->is_loaded, true)) {
            // Also we return a correct value here.
            // It's too hard to consistently match the CI bug where it returns void
            // (see the second is_loaded check in __load(), following the add_suffix dance).
            return $return ? $this->language : true;
        }

        if ($idiom == '') {
            $config = get_config();
            $idiom = $config['language'];
        }

        if ($module == '') {
            $module = CI::$APP->router->fetch_module();
        }

        $loaded = $this->__load($langfile, $this->fallback, $add_suffix, $alt_path, $module);

        if ($idiom != $this->fallback) {
            $loaded_native = $this->__load($langfile, $idiom, $add_suffix, $alt_path, $module);
            if ($loaded_native) {
                $loaded = array_merge($loaded, $loaded_native);
            } else {
                $missing_native = true;
                log_message('debug', "Unable to load the requested language file '$langfile' for current language '$idiom'.");
            }
        }

        if (empty($loaded)) {
            $fallbackLang = ucwords($this->fallback);
            show_error("Unable to load the requested language file '$langfile' for current language '$idiom' AND for fallback to '$fallbackLang'.");
        }

        if ($return) {
            return $loaded;
        }

        $this->is_loaded[] = $langfile.'_lang.php';
        $this->language = array_merge($this->language, $loaded);

        // Back-compat with CI return value, just in case apps check it.
        if (isset($missing_native) && $missing_native == true) {
            return false;
        }

        return true;
    }

    // @return array - empty means failure
    private function __load($langfile, $idiom, $add_suffix, $alt_path, $module)
    {
        $lang = array();

        list($path, $file) = Modules::find($langfile.'_lang', $module, 'language/'.$idiom.'/');
        if ($path) {
            // Module file
            $lang = Modules::load_file($file, $path, 'lang');

            // Save full path for debug message below
            $file = $path . $file;
        }

        if (empty($lang)) {
            // This code copied from CI Lang.  Obviously this means
            // passing $add_suffix=FALSE doesn't work for module files
            // (above); the resulting behaviour matches MX_Lang.
            $langfile = str_replace('.php', '', $langfile);

            if ($add_suffix == true) {
                // Extra period matches code from CI Lang
                // so in reality this won't work if you pass the '_lang' in.
                $langfile = str_replace('_lang.', '', $langfile).'_lang';
            }

            $langfile .= '.php';
            if (in_array($langfile, $this->is_loaded, true)) {
                // Inefficient, but this should only happen in the add_suffix=FALSE bugpath.
                // Note CI would incorrectly return void to the caller, but we need to
                // let load() know we suceeded otherwise it'll abort the script.
                return $this->language;
            }

            // Determine where the language file is and load it
            if ($alt_path != '' && file_exists($alt_path.'language/'.$idiom.'/'.$langfile)) {
                $file = $alt_path.'language/'.$idiom.'/'.$langfile;
                include($file);
            } else {
                foreach (get_instance()->load->get_package_paths(true) as $package_path) {
                    $file = $package_path.'language/'.$idiom.'/'.$langfile;
                    if (file_exists($file)) {
                        include $file;
                        if (! isset($lang)) {
                            log_message('error', "Language file contains no data? $file");
                        }
                        break;
                    }
                }
            }
        }

        if (! empty($lang)) {
            log_message('debug', "Bonfire MY_Lang: Language file loaded: '{$file}'");
        }

        return $lang;
    }

    /**
     * Language line
     *
     * Fetches a single line of text from the language array
     *
     * @param   string  $line       Language line key
     * @param   bool    $log_errors Whether to log an error message if the line is not found
     * @return  string  Translation
     */
    public function line($line, $log_errors = true)
    {
        $value = isset($this->language[$line]) ? $this->language[$line] : false;
        if ($value !== false) {
            return $value;
        }

        if ($log_errors === true) {
            log_message('error', "Could not find the language line '{$line}'");
        }

        return "FIXME('{$line}')";
    }
}
