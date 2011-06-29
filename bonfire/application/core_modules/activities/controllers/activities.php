<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Activities extends Admin_Controller {

	//--------------------------------------------------------------------
	// HMVC METHODS
	//--------------------------------------------------------------------
	
	public function activity_list($module=null, $limit=25) 
	{ 
		if (empty($module))
		{
			logit('No module provided to `activity_list`.');
			return;
		}
		$this->load->helper('date');
		$activities = $this->activity_model->order_by('created_on', 'desc')->limit($limit,0)->find_by_module($module);
		
		$this->load->view('activity_list', array('activities' => $activities));
	}
	
	//--------------------------------------------------------------------
	

}