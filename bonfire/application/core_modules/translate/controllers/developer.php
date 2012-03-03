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
	
		Template::set('languages', $this->langs);
		Template::set('lang_files', list_lang_files());
	
		Template::set('toolbar_title', lang('tr_translate_title'));
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
		
		Template::set('toolbar_title', lang('tr_edit_title') . ': '. $lang_file);
		
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
}

//--------------------------------------------------------------------