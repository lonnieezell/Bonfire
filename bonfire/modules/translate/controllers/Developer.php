<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Translate Module
 *
 * Manages the language files in Bonfire and allows an easy way for the user
 * to add language files for other languages.  The user can export current language
 * files for translation.
 *
 * @package Bonfire\Modules\Translate\Controllers\Developer
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs
 */
class Developer extends Admin_Controller
{
    /** @var array The names of the current languages. */
    private $langs;

    /**
     * Constructor - Load required classes
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Restrict access - View and Manage
        $this->auth->restrict('Bonfire.Translate.View');
        $this->auth->restrict('Bonfire.Translate.Manage');

        $this->lang->load('translate');

        $this->load->helper('languages');
        $this->langs = list_languages();

        Assets::add_module_js('translate', 'translate.js');
        Assets::add_module_css('translate', 'translate.css');

        Template::set_block('sub_nav', 'developer/_sub_nav');
    }

    /**
     * Display a list of all core language files, as well as a list of modules
     * the user can choose to edit.
     *
     * @param string $transLang The target language for translation.
     *
     * @return void
     */
    public function index($transLang = '')
    {
        if (empty($transLang)) {
            $config =& get_config();
            $transLang = isset($config['language']) ? $config['language'] : 'english';
        }

        // Selecting a different language?
        if (isset($_POST['select_lang'])) {
            $transLang = $this->input->post('trans_lang') == 'other' ?
                $this->input->post('new_lang') : $this->input->post('trans_lang');
        }

        // If the selected language is not in the list of languages, add it.
        if (! in_array($transLang, $this->langs)) {
            $this->langs[] = $transLang;
        }

        // Check whether there are custom modules with lang files.
        $allLangFiles = list_lang_files();
        if (isset($allLangFiles['custom'])) {
            $moduleLangFiles = $allLangFiles['custom'];
            sort($moduleLangFiles);
            Template::set('modules', $moduleLangFiles);
        }

        $coreLangFiles = $allLangFiles['core'];
        sort($coreLangFiles);
        Template::set('lang_files', $coreLangFiles);
        Template::set('languages', $this->langs);
        Template::set('trans_lang', $transLang);
        Template::set('toolbar_title', sprintf(lang('translate_title_index'), ucfirst($transLang)));
        Template::render();
    }

    /**
     * Allow the user to edit a language file.
     *
     * @param string $transLang The target language for translation.
     * @param string $langFile  The file to translate.
     *
     * @return void
     */
    public function edit($transLang = '', $langFile = '')
    {
        $config =& get_config();
        $origLang = isset($config['language']) ? $config['language'] : 'english';
        $chkd = array();

        if ($langFile) {
            // Save the file...
            if (isset($_POST['save'])) {
                // If the file saves successfully, redirect to the index.
                if (save_lang_file($langFile, $transLang, $_POST['lang'])) {
                    Template::set_message(lang('translate_save_success'), 'success');
                    redirect(SITE_AREA . "/developer/translate/index/{$transLang}");
                }

                Template::set_message(lang('translate_save_fail'), 'error');
            }

            // Get the lang file.
            $orig = load_lang_file($langFile, $origLang);
            $new = $transLang ? load_lang_file($langFile, $transLang) : $orig;

            // Translate.
            if (isset($_POST['translate']) && is_array($_POST['checked'])) {
                if (isset($_POST['lang'])) {
                    foreach ($_POST['lang'] as $key => $val) {
                        $new[$key] = $val;
                    }
                }

                if ($transLang) {
                    $this->load->config('langcodes');
                    $codes = $config['translate']['codes']['google'];
                    $transLangCode = isset($codes[$transLang]) ? $codes[$transLang] : '';
                    $origLangCode  = isset($codes[$origLang]) ? $codes[$origLang] : 'en';

                    $errcnt = 0;
                    $cnt = 0;
                    $this->load->library('translate_lib');

                    // Attempt to translate each of the selected values.
                    foreach ($_POST['checked'] as $key) {
                        $new[$key] = '* ' . $new[$key];
                        $val = '';
                        if ($transLangCode) {
                            $val = $this->translate_lib->setLang($origLangCode, $transLangCode)
                                                       ->translate($orig[$key]);
                        }
                        if ($val) {
                            $new[$key] = $val;
                            $cnt++;
                        } else {
                            $errcnt++;
                            $chkd[] = $key;
                        }

                        // Give up if 5 errors occur without any successful attempts,
                        // as this may imply network or other issues.
                        if ($errcnt >= 5 && $cnt == 0) {
                            break;
                        }
                    }

                    if ($errcnt == 0) {
                        Template::set_message(sprintf(lang('translate_success'), $cnt), 'success');
                    } elseif ($cnt > 0) {
                        Template::set_message(sprintf(lang('translate_part_success'), $cnt, $errcnt), 'info');
                    } else {
                        Template::set_message(lang('translate_failed'), 'error');
                    }
                }
            }

            Template::set('orig', $orig);
            Template::set('new', $new);
            Template::set('lang_file', $langFile);
        }

        Template::set('chkd', $chkd);
        Template::set('orig_lang', $origLang);
        Template::set('trans_lang', $transLang);
        Template::set('toolbar_title', sprintf(lang('translate_title_edit'), $langFile, ucfirst($transLang)));
        Template::render();
    }

    /**
     * Export a set of files for a language
     *
     * @return void
     */
    public function export()
    {
        if (isset($_POST['export'])) {
            $this->do_export($this->input->post('export_lang'), $this->input->post('include_core'), $this->input->post('include_custom'));
            die();
        }

        Template::set('languages', $this->langs);
        Template::set('toolbar_title', lang('translate_export'));
        Template::render();
    }

    /**
     * Retrieve all files for a language, zip them, and send the zip file to the
     * browser for immediate download
     *
     * @param string $language      The language for which to retrieve the files
     * @param bool $includeCore     Include the core language files
     * @param bool $includeCustom   Include the custom module language files
     *
     * @return mixed false on error or void
     */
    public function do_export($language = null, $includeCore = false, $includeCustom = false)
    {
        if (empty($language)) {
            $this->error = 'No language file chosen.';
            return false;
        }

        $all_lang_files = list_lang_files($language);
        if (! count($all_lang_files)) {
            $this->error = 'No files found to archive.';
            return false;
        }

        // Make the zip file
        $this->load->library('zip');
        foreach ($all_lang_files as $key => $file) {
            if (is_numeric($key) && $includeCore) {
                $content = load_lang_file($file, $language);
                $this->zip->add_data($file, save_lang_file($file, $language, $content, true));
            } elseif (($key == 'core' && $includeCore)
                      || ($key == 'custom' && $includeCustom)
            ) {
                foreach ($file as $f) {
                    $content = load_lang_file($f, $language);
                    $this->zip->add_data($f, save_lang_file($f, $language, $content, true));
                }
            }
        }

        $this->zip->download("bonfire_{$language}_files.zip");
        die();
    }
}
