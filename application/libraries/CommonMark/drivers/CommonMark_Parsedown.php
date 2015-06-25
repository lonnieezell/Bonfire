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
 * CommonMark driver for Parsedown v.1.5.1
 *
 * Adapter to use the Parsedown library within the Bonfire CommonMark library.
 *
 * @package Bonfire\Libraries\CommonMark\Drivers\CommonMark_Parsedown
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/developer/commonmark
 * @link    https://github.com/erusev/parsedown
 */
class CommonMark_Parsedown extends CommonMarkDriver
{
    /** @var string The class to instantiate and load into $this->converter. */
    protected $converterLib = 'Parsedown';

    /**
     * Load the Parsedown library.
     *
     * @return boolean Returns true to indicate the library has been loaded.
     */
    protected function init()
    {
        get_instance()->load->library('Parsedown');
        return true;
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
        return $this->converter->text($text);
    }
}
