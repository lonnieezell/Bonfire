<?php

/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2013, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

namespace Bonfire;

/**
 * Template Interface
 *
 * Provides a consistent manner to implement different template engines
 * without having to make sweeping changes to your application.
 */
interface TemplateInterface
{

    /**
     * The main entryway into rendering a view. This is called from the
     * controller and is generally the last method called.
     *
     * @param string $layout If provided, will override the default layout.
     */
    public function render ($layout = NULL);

    //--------------------------------------------------------------------

    /**
     * Used within the template layout file to render the current content.
     * This content is typically used to display the current view.
     */
    public function content ();

    //--------------------------------------------------------------------

    /**
     * Sets the active theme to use. This theme should be
     * relative to one of the 'theme_paths' folders.
     *
     * @param $theme
     */
    public function setTheme ($theme);

    //--------------------------------------------------------------------

    /**
     * Sets the current view file to render.
     *
     * @param $file
     * @return mixed
     */
    public function setView ($file);

    //--------------------------------------------------------------------

    /**
     * Stores one or more pieces of data to be passed to the views when
     * they are rendered out.
     *
     * If both $key and $value are ! empty, then it will treat it as a
     * key/value pair. If $key is an array of key/value pairs, then $value
     * is ignored and each element of the array are made available to the
     * view as if it was a single $key/$value pair.
     *
     * @param string|array $key
     * @param mixed        $value
     */
    public function set ($key, $value = NULL);

    //--------------------------------------------------------------------

    /**
     * Returns a value that has been previously set().
     *
     * @param $key
     * @return mixed
     */
    public function get ($key);

    //--------------------------------------------------------------------

    /**
     * Determines whether or not the view should be parsed with the
     * CodeIgniter's parser.
     *
     * @param bool $parse
     * @return mixed
     */
    public function parseViews ($parse = FALSE);

    //--------------------------------------------------------------------

    /**
     * Theme paths allow you to have multiple locations for themes to be
     * stored. This might be used for separating themes for different sub-
     * applications, or a core theme and user-submitted themes.
     *
     * @param $path A new path where themes can be found.
     */
    public function addThemePath ($path);

    //--------------------------------------------------------------------

    /**
     * Removes a single theme path.
     *
     * @param $path
     */
    public function removeThemePath ($path);
    //--------------------------------------------------------------------

}