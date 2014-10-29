<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Authenticated Controller
 *
 * Provides a base class for all controllers that must check user login
 * status.
 *
 * @package    Bonfire\Core\Controllers
 * @category   Controllers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Authenticated_Controller extends Base_Controller
{

    protected $require_authentication   = true;


	//--------------------------------------------------------------------

	/**
	 * Class constructor setup login restriction and load various libraries
	 *
	 */
	public function __construct()
	{
        $this->autoload['helpers'][]    = 'form';
        $this->autoload['libraries'][]  = 'Template';
        $this->autoload['libraries'][]  = 'Assets';
        $this->autoload['libraries'][]  = 'form_validation';

		parent::__construct();

		$this->form_validation->set_error_delimiters('', '');

	}//end construct()

	//--------------------------------------------------------------------

}

/* End of file Authenticated_Controller.php */
/* Location: ./application/core/Authenticated_Controller.php */