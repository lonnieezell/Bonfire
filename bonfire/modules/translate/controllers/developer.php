<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2012, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Translate Module
 *
 * Manages the language files in Bonfire and allows an easy way for the user
 * to add language files for other languages.  The user can export current language
 * files for translation.
 *
 * @package    Bonfire
 * @subpackage Modules_Translate
 * @category   Controllers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Developer extends Admin_Controller
{

	/**
	 * Array of current languages
	 *
	 * @var array
	 */
	private $langs;

	//--------------------------------------------------------------------

	/**
	 * Loads required classes
	 *
	 * @todo Add permission restrictions
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		// restrict access - View and Manage
		$this->auth->restrict('Bonfire.Translate.View');
		$this->auth->restrict('Bonfire.Translate.Manage');

		$this->lang->load('translate');

		$this->load->helper('languages');
		$this->langs = list_languages();

		Template::set_block('sub_nav', 'developer/_sub_nav');

	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * Displays a list of all core language files, as well as a list of
	 * modules that the user can choose to edit.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function index($trans_lang='english')
	{
		Assets::add_module_js('translate', 'translate.js');

		// Selecting a different language?
		if ($this->input->post('select_lang'))
		{
			$trans_lang = $this->input->post('trans_lang');

			// Other?
			if ($trans_lang == 'other')
			{
				$trans_lang = $this->input->post('new_lang');
			}
		}//end if

		if (!in_array($trans_lang, $this->langs))
		{
			$this->langs[] = $trans_lang;
		}

		$all_lang_files = list_lang_files();
		Template::set('languages', $this->langs);
		Template::set('lang_files', $all_lang_files['core']);

		// check that we have custom modules
		if (isset($all_lang_files['custom']))
		{
			Template::set('modules', $all_lang_files['custom']);
		}

		Template::set('trans_lang', $trans_lang);
		Template::set('toolbar_title', lang('tr_translate_title') .' to '. ucfirst($trans_lang));
		Template::render();

	}//end index()

	//--------------------------------------------------------------------

	/**
	 * Allow the user to edit a language file
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function edit($trans_lang='', $lang_file='')
	{
		// Save the file...
		if ($lang_file && $this->input->post('submit'))
		{
			if (save_lang_file($lang_file, $trans_lang, $_POST['lang']))
			{
				Template::set_message(lang('tr_save_success'), 'success');
				redirect(SITE_AREA .'/developer/translate/index/'. $trans_lang);
			}
			else
			{
				Template::set_message(lang('tr_save_fail'), 'error');
			}
		}

		// Get the lang file
		if ($lang_file)
		{
			$orig	= load_lang_file($lang_file, 'english');
			$new	= load_lang_file($lang_file, $trans_lang);

			if (!$new)
			{
				$new = $orig;
			}

			Template::set('orig', $orig);
			Template::set('new', $new);
			Template::set('lang_file', $lang_file);
		}

		Template::set('trans_lang', $trans_lang);
		Template::set('toolbar_title', lang('tr_edit_title') .' to '. ucfirst($trans_lang) . ': '. $lang_file);

		Template::render();

	}//end edit()

	//--------------------------------------------------------------------

	/**
	 * Export a set of files for a language
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function export()
	{
		if ($this->input->post('submit'))
		{
			$language = $this->input->post('export_lang');
            $this->do_export($language, $this->input->post('include_core'), $this->input->post('include_custom'));
            die();
		}

		Template::set('languages', $this->langs);

		Template::set('toolbar_title', lang('tr_export'));
		Template::render();

	}//end export()

	//--------------------------------------------------------------------

	/**
	 * Retrieve all files for a language, zip them and send the zip file
	 * to the browser for immediate download
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function do_export($language=NULL, $include_core=FALSE, $include_custom=FALSE)
	{
		if (empty($language))
		{
			$this->error = 'No language file chosen.';
			return FALSE;
		}

		$all_lang_files = list_lang_files($language);

		if (!count($all_lang_files))
		{
			$this->error = 'No files found to archive.';
			return FALSE;
		}

		// Make the zip file
		$this->load->library('zip');

		foreach ($all_lang_files as $key => $file)
        {
            if (is_numeric($key) && $include_core)
            {
                $content = load_lang_file($file, $language);
                $this->zip->add_data($file, save_lang_file($file, $language, $content, TRUE));
            }
            else if ($key == 'core' && $include_core)
            {
                foreach ($file as $f)
                {
                    $content = load_lang_file($f, $language);
                    $this->zip->add_data($f, save_lang_file($f, $language, $content, TRUE));
                }
            }
            else if ($key == 'custom' && $include_custom)
            {
                foreach ($file as $f)
                {
                    $content = load_lang_file($f, $language);
                    $this->zip->add_data($f, save_lang_file($f, $language, $content, TRUE));
                }
            }
        }//end foreach

		$this->zip->download('bonfire_'. $language .'_files.zip');
        die();

	}//end do_export()

	//--------------------------------------------------------------------

}//end Developer
