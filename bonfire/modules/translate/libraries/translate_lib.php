<?php  defined('BASEPATH') or exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * Translate Module Library
 *
 * Provides methods to retrieve translations from external sites
 * Requires library Curl.php
 *
 * @package    Bonfire
 * @subpackage Modules_Translate
 * @category   Libraries
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/
 * @TODO       Update File Link to a Docs/Guides on the translate_lib methods
 *
 */
class Translate_lib
{

	/**
	 * A pointer to the CodeIgniter instance.
	 *
	 * @access protected
	 *
	 * @var object
	 */
	protected $ci;

	/**
	 * Last translation result
	 * @var string
	 * @access private
	 */
	public $lastResult = "";
	
	/**
	 * Language engine for translation
	 * @var string
	 * @access private
	 */
	private $langEngine='google';
	
	/**
	 * Language code translating from
	 * @var string
	 * @access private
	 */
	private $langFrom;
	
	/**
	 * Language code translating to
	 * @var string
	 * @access private
	 */
	private $langTo;
	
	/**
	 *  URL formats (Google Translate, others)
	 * @var array
	 * @access private
	 */
	private static $urlFormat = array("google"=> 
		array('url'=>"http://translate.google.com/translate_a/t?client=t&text=%s&hl=en&sl=%s&tl=%s&ie=UTF-8&oe=UTF-8&multires=1&otf=1&pc=1&trs=1&ssel=3&tsel=6&sc=1",
		"langcodes"=> array('')
		),    
		);

	// ------------------------------------------------------------------------
	/**
	 * The Translate Construct allows optionally passing settings in array and stores them
	 *
	 * @param array containing:
	 * 'engine' Language engine for translation (Optional)
	 * , string 'from' Language code translating from (Optional)
	 * , string 'to' Language code translating to (Optional)
	 * @access public
	 * @return void
	 */
	public function __construct($a=array())
	{

		$this->ci =& get_instance();
		if (isset($a['engine'])) $this->setEngine($a['engine']);
		if (isset($a['from']) && isset($a['to'])) $this->setLang($a['from'],$a['to']);
		else if (isset($a['from'])) $this->setLang($a['from']);
		else if (isset($a['to'])) $this->setLang($this->langFrom,$a['engine']);

	}//end __construct()

	// ------------------------------------------------------------------------


	/**
	 * Set language engine
	 * 
	 * @param string $engine Language Engine
	 * @return this
	 * @access public
	 */
	public function setEngine($engine) {
		$this->langEngine = $engine;
		return $this;
	}
	
	/**
	 * Set language codes
	 * 
	 * @param string $from Language code
	 * @param string $to Language code
	 * @return this 
	 * @access public
	 */
	public function setLang($from, $to='en') {
		$this->langFrom = $from;
		$this->langTo = $to;
		return $this;
	}
	

	/**
	 * Retrieves a translation.
	 *
	 * @access public
	 *
	 *
	 * @param string  Text to be translated
	 * @return mixed - string Translated text OR false if failure
	 *
	 * @return bool
	 */
	public function translate($text)
	{
		$ci =& get_instance();
		$ci->load->library('curl');
		
		$url = sprintf(self::$urlFormat[$this->langEngine]['url'], rawurlencode($text), $this->langFrom, $this->langTo);
		$result = $ci->curl->simple_get($url);
		if (!$result) return false;
		$result = preg_replace('!,+!', ',', $result); // remove repeated commas (causing JSON syntax error)
		$result = str_replace('[,', '[', $result); // remove extra comma after bracket (also causing JSON syntax error)
		$resultArray = json_decode($result, true);
		$finalResult = "";
		if (!isset($resultArray[0])) return false;
		foreach ($resultArray[0] as $results)
			$finalResult .= $results[0];
		return $this->lastResult = $finalResult;
	}//end translate()

	// ------------------------------------------------------------------------


	
}
/* End of class Translate_lib.php */
?>