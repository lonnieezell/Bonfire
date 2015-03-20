<?php

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License.
 * @link      http://cibonfire.com
 * @since     0.7.2
 * @filesource
 */

/**
 * CommonMark Driver for PHP Markdown Lib v1.5
 *
 * Adapter to use the Markdown library within the Bonfire CommonMark library.
 *
 * @package Bonfire\Libraries\CommonMark\Drivers\CommonMark_Markdown
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/developer/commonmark
 * @link    https://github.com/michelf/php-markdown
 */
class CommonMark_Markdown extends CommonMarkDriver
{
    /** @var string The class to instantiate and load into $this->converter. */
    protected $converterLib = '\Michelf\Markdown';

    /** @var array The name(s) of the file(s) to load the library manually. */
    protected $files = array('Markdown.inc.php');

    /**
     * Set the paths array, in case the library must be loaded manually.
     */
    public function __construct()
    {
        $this->paths = array(
            APPPATH . 'vendor/michelf/php-markdown/Michelf',
            APPPATH . '../vendor/michelf/php-markdown/Michelf',
            APPPATH . 'third_party/michelf/php-markdown/Michelf',
            APPPATH . 'third_party/Michelf',
        );
    }

    /**
     * The library method used to convert CommonMark to HTML.
     *
     * @param string $text CommonMark text to convert.
     *
     * @return string HTML text.
     */
    protected function toHtml($text)
    {
        return $this->converter->defaultTransform($text);
    }
}
