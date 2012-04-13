<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Developer extends Admin_Controller {
	
	private $trans_lang = 'english';
	private $langs;
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->lang->load('translate');
		
		$this->load->helper('languages');
		$this->langs = list_languages();
		
		Assets::add_module_js('translate', 'translate.js');
		
		// Which language are we translating to?
		$this->trans_lang = $this->input->get('lang') ? $this->input->get('lang') : 'english';
		Template::set('trans_lang', $this->trans_lang);
		
		if (!in_array($this->trans_lang, $this->langs))
		{
			$this->langs[] = $this->trans_lang;
		}
		
		Template::set_block('sub_nav', 'developer/_sub_nav');
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: index()
		
		Displays a list of all core language files, as well as a list of 
		modules that the user can choose to edit.
	*/
	public function index() 
	{ 
		// Selecting a different language? 
		if ($this->input->post('select_lang'))
		{ 
			$this->trans_lang = $this->input->post('trans_lang');
			
			// Other?
			if ($this->trans_lang == 'other')
			{
				$this->trans_lang = $this->input->post('new_lang');
			}
			
			Template::set('trans_lang', $this->trans_lang);
			
			if (!in_array($this->trans_lang, $this->langs))
			{
				$this->langs[] = $this->trans_lang;
			}
		}

		$all_lang_files = list_lang_files();
		Template::set('languages', $this->langs);
		Template::set('lang_files', $all_lang_files['core']);
		Template::set('modules', $all_lang_files['custom']);

		Template::set('toolbar_title', lang('tr_translate_title') .' to '. ucfirst($this->trans_lang));
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function edit() 
	{
		$lang_file = $this->input->get('file');
		
		// Save the file...
		if ($lang_file && $this->input->post('submit'))
		{
			if (save_lang_file($lang_file, $this->trans_lang, $_POST['lang']))
			{
				Template::set_message(lang('tr_save_success'), 'success');
				redirect(SITE_AREA .'/developer/translate?lang='. $this->trans_lang);
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
			$new	= load_lang_file($lang_file, $this->trans_lang);
			
			if (!$new) 
			{
				$new = $orig;
			}
			
			Template::set('orig', $orig);
			Template::set('new', $new);
			Template::set('lang_file', $lang_file);
		}
		
		Template::set('toolbar_title', lang('tr_edit_title') .' to '. ucfirst($this->trans_lang) . ': '. $lang_file);
		
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
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
	}
	
	//--------------------------------------------------------------------
	
	public function do_export($language=null, $include_core=false, $include_custom=false)
	{
		if (empty($language))
		{
			$this->error = 'No language file chosen.';
			return false;
		}
		
		$all_lang_files = list_lang_files($language);
		
		if (!count($all_lang_files))
		{
			$this->error = 'No files found to archive.';
			return false;
		}
		
		// Make the zip file 
		$this->load->library('zip');
		
		foreach ($all_lang_files as $key => $file)
        {
            if (is_numeric($key) && $include_core)
            {
                $content = load_lang_file($file, $language);
                $this->zip->add_data($file, save_lang_file($file, $language, $content, true));
            }
            else if ($key == 'core' && $include_core)
            {
                foreach ($file as $f)
                {
                    $content = load_lang_file($f, $language);
                    $this->zip->add_data($f, save_lang_file($f, $language, $content, true));
                }
            }
            else if ($key == 'custom' && $include_custom)
            {
                foreach ($file as $f)
                {
                    $content = load_lang_file($f, $language);
                    $this->zip->add_data($f, save_lang_file($f, $language, $content, true));
                }
            }
        }

		$this->zip->download('bonfire_'. $language .'_files.zip');
        die();
	}
	
	//--------------------------------------------------------------------
	
}