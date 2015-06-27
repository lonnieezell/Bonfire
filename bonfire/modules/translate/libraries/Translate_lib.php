<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Translate Module Library
 *
 * Provides methods to retrieve translations from external sites.
 *
 * @uses Bonfire\Libraries\Curl (CodeIgniter cURL library from Phil Sturgeon)
 *
 * @package Bonfire\Modules\Translate\Libraries\Translate_lib
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs
 * @todo    Update Link to a Docs/Guides on the translate_lib methods
 */
class Translate_lib
{
    /** @var object A pointer to the CodeIgniter instance. */
    protected $ci;

    /** @var string Last translation result. */
    public $lastResult = '';

    /** @var string Language engine for translation. */
    private $langEngine = 'google';

    /** @var string Language code translating from. */
    private $langFrom;

    /** @var string Language code translating to. */
    private $langTo;

    /** @var array URL formats (Google Translate, others). */
    private static $urlFormat = array(
        'google' => array(
            'url' => 'http://translate.google.com/translate_a/t?client=t&text=%s&hl=en&sl=%s&tl=%s&ie=UTF-8&oe=UTF-8&multires=1&otf=1&pc=1&trs=1&ssel=3&tsel=6&sc=1',
            'langcodes' => array(''),
        ),
    );

    // ------------------------------------------------------------------------

    /**
     * The Translate Construct allows optionally passing settings in array and stores them
     *
     * @param array $options containing:
     * 'engine' Language engine for translation (Optional)
     * string 'from' Language code translating from (Optional)
     * string 'to' Language code translating to (Optional)
     *
     * @return void
     */
    public function __construct($options = array())
    {
        $this->ci =& get_instance();

        if (isset($options['engine'])) {
            $this->setEngine($options['engine']);
        }

        if (isset($options['from'])) {
            if (isset($options['to'])) {
                $this->setLang($options['from'], $options['to']);
            } else {
                $this->setLang($options['from']);
            }
        } elseif (isset($options['to'])) {
            $this->setLang($this->langFrom, $options['engine']);
        }
    }

    /**
     * Set language engine
     *
     * @param string $engine Language Engine
     *
     * @return this
     */
    public function setEngine($engine)
    {
        $this->langEngine = $engine;
        return $this;
    }

    /**
     * Set language codes
     *
     * @param string $from Language code
     * @param string $to Language code
     *
     * @return this
     */
    public function setLang($from, $to = 'en')
    {
        $this->langFrom = $from;
        $this->langTo = $to;
        return $this;
    }

    /**
     * Retrieves a translation.
     *
     * @param string $text Text to be translated
     *
     * @return string|bool  Translated text OR false if failure
     */
    public function translate($text)
    {
        $this->ci->load->library('curl');
        $url = sprintf(self::$urlFormat[$this->langEngine]['url'], rawurlencode($text), $this->langFrom, $this->langTo);
        $result = $this->ci->curl->simple_get($url);
        if (! $result) {
            return false;
        }

        // Clean result to prevent JSON syntax errors
        $result = preg_replace('!,+!', ',', $result); // Remove repeated commas
        $result = str_replace('[,', '[', $result); // Remove extra comma after bracket
        $resultArray = json_decode($result, true);
        if (! isset($resultArray[0])) {
            return false;
        }

        $finalResult = '';
        foreach ($resultArray[0] as $results) {
            $finalResult .= $results[0];
        }

        return $this->lastResult = $finalResult;
    }
}
