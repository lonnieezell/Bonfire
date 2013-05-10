<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Dwoo Parser Class
 *
 * @package     Bonfire\Core\Libraries
 * @category    Parser
 * @author      Avinash Kundaliya
 * @link        http://cibonfire.com
 */
class MY_Parser extends CI_Parser
{
    /**
     * An instance of the CI super object.
     *
     * @access private
     *
     * @var object
     */
    private $ci;

    // ------------------------------------------------------------------------

    /**
     * Load dependacies and sets CI major object.
     *
     * @return void
     */
    function __construct()
    {

        //include the lex parser class
        if ( ! class_exists('Lex_Autoloader'))
        {
            include APPPATH.'/libraries/Lex/Autoloader.php';
        }

        $this->ci =& get_instance();
    }

    // ------------------------------------------------------------------------

    /**
     * Parses Template or String and does the Mojo!
     *
     * @param  string  $template  View File or String to Parse
     * @param  array   $data      Array of Data to be Parsed
     * @param  boolean $return
     * @param  boolean $load_view
     * @return mixed
     */
    public function parse($template = '', $data = array(), $return = FALSE, $load_view = TRUE)
    {

        // Ready Set Go!
        $this->ci->benchmark->mark('parse_start');

        // Convert from object to array
        is_array($data) or $data = (array) $data;

        $data = array_merge($data, $this->ci->load->_ci_cached_vars);

        //if load_view is false, we parse the string
        $parseString = $template;

        //else load the view to parse
        if($load_view)
        {
            $parseString = $this->ci->load->view($template, $data, TRUE);
        }

        Lex_Autoloader::register();

        $parser = new Lex_Parser();
        $parser->scopeGlue(':');

        $parsed = $parser->parse($parseString, $data, array($this, 'parser_callback'));

        // Time's Up!
        $this->ci->benchmark->mark('parse_end');

        if ( ! $return)
        {
            $this->ci->output->append_output($parsed);
            return;
        }

        return $parsed;

    }

    // ------------------------------------------------------------------------

    /**
     * Parser Callback
     *
     * @param  string $module
     * @param  string $attribute
     * @param  string $content
     *
     * @return mixed
     */
    public function parser_callback($module, $attribute, $content)
    {
        $return_view = NULL;
        $parsed_return = '';

        $output = self::get_view($module,$attribute);
        $return_view = $output;

        //loop it up, if its array no use in the template, gotta work it here.
        if(is_array($output))
        {
            // Need to make sure we have a array and no objects inside the array too.
            $parser = new Lex_Parser();
            $parser->scopeGlue(':');

            foreach($output as $result)
            {
                $parsed_return .= $parser->parse($content, $result, array($this, 'parser_callback'));
            }

            unset($parser);

            $return_view =  $parsed_return;
        }

        return $return_view;
    }

    // ------------------------------------------------------------------------


    /**
     * Runs module or library callback methods.
     *
     * @access private
     *
     * @param  string $module    Module Class Name
     * @param  array  $attribute Attributes to run Method with
     * @param  string $method    Method to call.
     *
     * @return mixed
     */
    private function get_view($module = '', $attribute = array(), $method = 'index')
    {
        $return_view = false;

        // Get the required module
        $module = str_replace(':','/',$module);

        if(($pos = strrpos($module, '/')) != FALSE)
        {
            $method = substr($module, $pos + 1);
            $module = substr($module, 0, $pos);
        }

        if($class = $this->ci->load->module($module))
        {
            //if the method is callable
            if (method_exists($class, $method))
            {
                ob_start();
                $output = call_user_func_array(array($class, $method), $attribute);
                $buffer = ob_get_clean();
                $output = ($output !== NULL) ? $output : $buffer;

                $return_view = $output;
            }
        }

        //maybe it is a library
        else if(!$return_view && strpos($module,'/') === FALSE)
        {
            if(class_exists($module))
            {
                ob_start();
                $output = call_user_func_array(array($module, $method), $attribute);
                $buffer = ob_get_clean();
                $output = ($output !== NULL) ? $output : $buffer;

                $return_view = $output;
            }

        }

        return $return_view;

    }
}