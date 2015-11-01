<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package     CodeIgniter
 * @author      ExpressionEngine Dev Team
 * @copyright   Copyright © 2008 - 2011, EllisLab, Inc.
 * @license     http://codeigniter.com/user_guide/license.html
 * @link        http://codeigniter.com
 * @since       Version 1.0
 * @filesource
 */

/**
 * CodeIgniter Profiler Class
 *
 * This class enables you to display benchmark, query, and other data in order to
 * help with debugging and optimization.
 *
 * Note: At some point it would be good to move all the HTML in this class into
 * a set of template files in order to allow customization.
 *
 * @package     CodeIgniter\Libraries\Profiler
 * @author      ExpressionEngine Dev Team
 * @link        http://codeigniter.com/user_guide/general/profiling.html
 */
class CI_Profiler
{
    public $CI;

    protected $_available_sections = array(
        'benchmarks',
        'get',
        'memory_usage',
        'post',
        'uri_string',
        'controller_info',
        'queries',
        'http_headers',
        'config',
        'files',
        'console',
        'userdata',
    );

    protected $_query_toggle_count = 25;

    /** @var array Results from _compile_x(). */
    protected $_sections = array();

    /** @var string Benchmark time format for display - either 'sec' or 'ms'. */
    protected $_time_format = 'ms';

    // -------------------------------------------------------------------------

    public function __construct($config = array())
    {
        $this->CI =& get_instance();
        $this->CI->load->language('profiler');

        if (isset($config['query_toggle_count'])) {
            $this->_query_toggle_count = (int) $config['query_toggle_count'];
            unset($config['query_toggle_count']);
        }

        // Default all sections to display.
        foreach ($this->_available_sections as $section) {
            if (! isset($config[$section])) {
                $this->_compile_{$section} = true;
            }
        }

        // Make sure the Console is loaded.
        if (! class_exists('Console')) {
            $this->load->library('Console');
        }

        $this->set_sections($config);
    }

    /**
     * Set the private _compile_* properties to enable/disable Profiler sections.
     *
     * @param array The section names (keys) and their enable/disable state (values).
     *
     * @return  void
     */
    public function set_sections($config)
    {
        foreach ($config as $method => $enable) {
            if (in_array($method, $this->_available_sections)) {
                $this->_compile_{$method} = ($enable !== false);
            }
        }
    }

    /**
     * Compile benchmarks.
     *
     * This function cycles through the entire array of mark points and matches
     * any two points that are named identically (ending in "_start" and "_end"
     * respectively). It then compiles the execution times for all points and returns
     * them in an array.
     *
     * @return  array
     */
    protected function _compile_benchmarks()
    {
        $profile = array();
        foreach ($this->CI->benchmark->marker as $key => $val) {
            // Match the "end" marker so that the list ends up in the defined order.
            if (preg_match("/(.+?)_end/i", $key, $match)) {
                if (isset($this->CI->benchmark->marker["{$match[1]}_end"])
                    && isset($this->CI->benchmark->marker["{$match[1]}_start"])
                ) {
                    $time = $this->CI->benchmark->elapsed_time("{$match[1]}_start", $key);
                    if ($this->_time_format == 'ms') {
                        $time = round($time * 1000) . ' ms';
                    }

                    $profile[$match[1]] = $time;
                }
            }
        }

        // Build a table containing the profile data.
        // Note: Making this data available to be logged might be useful.

        $output = array();
        foreach ($profile as $key => $val) {
            $key = ucwords(str_replace(array('_', '-'), ' ', $key));
            $output[$key] = $val;
        }

        return $output;
    }

    /**
     * Compile Queries
     *
     * @return array
     */
    protected function _compile_queries()
    {
        $dbs = array();

        // Determine the currently connected database(s).
        foreach (get_object_vars($this->CI) as $CI_object) {
            if (is_object($CI_object)
                && is_subclass_of(get_class($CI_object), 'CI_DB')
            ) {
                $dbs[] = $CI_object;
            }
        }

        if (count($dbs) == 0) {
            return $this->CI->lang->line('profiler_no_db');
        }

        $highlight = array(
            'SELECT', 'DISTINCT', 'FROM', 'WHERE', 'and', 'LEFT JOIN', 'ORDER BY',
            'GROUP BY', 'LIMIT', 'INSERT', 'INTO', 'VALUES', 'UPDATE', 'OR ',
            'HAVING', 'OFFSET', 'NOT IN', ' IN', 'LIKE', 'NOT LIKE',
            'COUNT', 'MAX', 'MIN', ' ON', ' AS ', 'AVG', 'SUM', '(', ')'
        );

        $output = array();
        foreach ($dbs as $db) {
            if (count($db->queries) == 0) {
                $output[] = $this->CI->lang->line('profiler_no_queries');
            } else {
                $total  = 0; // total query time
                $counts = array_count_values($db->queries);
                $explainSupported = stripos($this->CI->db->platform(), 'mysql') !== false;
                $explainPartial = $explainSupported && version_compare($this->CI->db->version(), '5.6.3', '<');

                foreach ($db->queries as $key => $val) {
                    $duplicate = false;
                    $time = number_format($db->query_times[$key], 4);
                    $query = $duplicate ? "<span class='ci-profiler-duplicate'>{$val}</span>" : $val;

                    $explain = $explainSupported
                        && stripos($val, 'SELECT') !== false
                        && ! ($explainPartial && preg_match('/UPDATE|INSERT|DELETE/i', $val)
                        ) ? $this->CI->db->query("EXPLAIN {$val}") : null;
                    if (! is_null($explain)) {
                        $query .= $this->build_sql_explain($explain->row(), $time);
                    }

                    $total += $db->query_times[$key];
                    foreach ($highlight as $bold) {
                        $query = str_ireplace($bold, "<strong>{$bold}</strong>", $query);
                    }

                    $output[] = array(
                        'query' => $query,
                        'time'  => $time
                    );
                }

                $total = number_format($total, 4);
                $output[][$total] = 'Total Query Execution Time';
            }
        }

        return $output;
    }

    public function build_sql_explain($data, $time)
    {
        $output = "<span class='ci-profiler-db-explain'>" .
            "Speed: <em>{$time}</em>" .
            " - Possible keys: <em>{possible_keys}</em>" .
            " - Key Used: <em>{key}</em>" .
            " - Type: <em>{type}</em>" .
            " - Rows: <em>{rows}</em>" .
            " - Extra: <em>{Extra}</em>" .
            "</span>";

        foreach (array('possible_keys', 'key', 'type', 'rows', 'Extra') as $key) {
            $output = str_replace(
                '{' . $key . '}',
                htmlentities($data->{$key}, ENT_QUOTES, 'UTF-8'),
                $output
            );
        }

        return $output;
    }

    /**
     * Compile $_GET Data
     *
     * @return array/string
     */
    protected function _compile_get()
    {
        if (count($_GET) == 0) {
            return $this->CI->lang->line('profiler_no_get');
        }

        $output = array();
        foreach ($_GET as $key => $val) {
            if (! is_numeric($key)) {
                $key = "'{$key}'";
            }

            $output["&#36;_GET[{$key}]"] = is_array($val) ?
                 "<pre>" . htmlspecialchars(stripslashes(print_r($val, true))) . "</pre>"
                 : htmlspecialchars(stripslashes($val));
        }

        return $output;
    }

    /**
     * Compile $_POST Data
     *
     * @return array/string
     */
    protected function _compile_post()
    {
        if (count($_POST) == 0) {
            return $this->CI->lang->line('profiler_no_post');
        }

        $output = array();
        foreach ($_POST as $key => $val) {
            if (! is_numeric($key)) {
                $key = "'{$key}'";
            }

            $output["&#36;_POST[{$key}]"] = is_array($val) ?
                '<pre>' . htmlspecialchars(stripslashes(print_r($val, true))) . '</pre>'
                : htmlspecialchars(stripslashes($val));
        }

        return $output;
    }

    /**
     * Show query string
     *
     * @return  string
     */
    protected function _compile_uri_string()
    {
        return $this->CI->uri->uri_string == '' ?
            $this->CI->lang->line('profiler_no_uri') : $this->CI->uri->uri_string;
    }

    /**
     * Show the controller and function that were called
     *
     * @return  string
     */
    protected function _compile_controller_info()
    {
        return $this->CI->router->class . '/' . $this->CI->router->fetch_method();
    }

    /**
     * Compile memory usage
     *
     * Display total used memory
     *
     * @return string
     */
    protected function _compile_memory_usage()
    {
        if (function_exists('memory_get_usage')
            && '' != ($usage = memory_get_usage())
        ) {
            return number_format($usage) . ' bytes';
        }

        return $this->CI->lang->line('profiler_no_memory_usage');
    }

    /**
     * Compile header information
     *
     * Lists HTTP headers
     *
     * @return  string
     */
    protected function _compile_http_headers()
    {
        $output = array();
        foreach (array(
            'HTTP_ACCEPT', 'HTTP_USER_AGENT', 'HTTP_CONNECTION', 'SERVER_PORT',
            'SERVER_NAME', 'REMOTE_ADDR', 'SERVER_SOFTWARE', 'HTTP_ACCEPT_LANGUAGE',
            'SCRIPT_NAME', 'REQUEST_METHOD',' HTTP_HOST', 'REMOTE_HOST', 'CONTENT_TYPE',
            'SERVER_PROTOCOL', 'QUERY_STRING', 'HTTP_ACCEPT_ENCODING', 'HTTP_X_FORWARDED_FOR'
        ) as $header) {
            $output[$header] = isset($_SERVER[$header]) ? $_SERVER[$header] : '';
        }

        return $output;
    }

    /**
     * Compile config information
     *
     * Lists developer config variables
     *
     * @return  string
     */
    protected function _compile_config()
    {
        $output = array();
        foreach ($this->CI->config->config as $config => $val) {
            if ($val === true) {
                $output[$config] = $this->CI->lang->line('bf_profiler_true');
            } elseif ($val === false) {
                $output[$config] = $this->CI->lang->line('bf_profiler_false');
            } elseif (is_array($val)) {
                $output[$config] = htmlspecialchars(stripslashes(print_r($val, true)));
            } else {
                $output[$config] = htmlspecialchars(stripslashes($val));
            }
        }

        return $output;
    }

    public function _compile_files()
    {
        $files = get_included_files();
        sort($files);

        return $files;
    }

    public function _compile_console()
    {
        $logs = Console::get_logs();
        if ($logs['console']) {
            foreach ($logs['console'] as $key => $log) {
                if ($log['type'] == 'log') {
                    $logs['console'][$key]['data'] = print_r($log['data'], true);
                } elseif ($log['type'] == 'memory') {
                    $logs['console'][$key]['data'] = $this->get_file_size($log['data']);
                }
            }
        }

        return $logs;
    }

    public function _compile_userdata()
    {
        if (! isset($this->CI->session)) {
            return '';
        }

        $compiled_userdata = $this->CI->session->all_userdata();
        if (empty($compiled_userdata)) {
            return '';
        }

        $output = array();
        foreach ($compiled_userdata as $key => $val) {
            if ($val === true) {
                $output[$key] = $this->CI->lang->line('bf_profiler_true');
            } elseif ($val === false) {
                $output[$key] = $this->CI->lang->line('bf_profiler_false');
            } elseif (is_array($val)) {
                $output[$key] = htmlspecialchars(stripslashes(print_r($val, true)));
            } else {
                $output[$key] = htmlspecialchars(stripslashes($val));
            }
        }

        return $output;
    }

    public static function get_file_size($size, $retstring = null)
    {
        // adapted from code at http://aidanlister.com/repos/v/function.size_readable.php
        if ($retstring === null) {
            $retstring = '%01.2f %s';
        }

        $sizes = array('bytes', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $lastsizestring = end($sizes);
        foreach ($sizes as $sizestring) {
            if ($size < 1024) {
                break;
            }
            if ($sizestring != $lastsizestring) {
                $size /= 1024;
            }
        }

        // Bytes aren't normally fractional.
        if ($sizestring == $sizes[0]) {
            $retstring = '%01d %s';
        }

        return sprintf($retstring, $size, $sizestring);
    }

    /**
     * Run the Profiler
     *
     * @return  string
     */
    public function run()
    {
        $this->CI->load->helper('language');
        $fields_displayed = 0;

        // Run each _compile_* method and add the results to the $_sections array.
        foreach ($this->_available_sections as $section) {
            if ($this->_compile_{$section} !== false) {
                $func = "_compile_{$section}";
                if ($section == 'http_headers') {
                    $section = 'headers';
                }
                $this->_sections[$section] = $this->{$func}();
                ++$fields_displayed;
            }
        }

        // Has the user created an override in application/views?
        if (is_file(APPPATH . 'views/profiler_template.php')) {
            $output = $this->CI->load->view(
                'profiler_template',
                array(
                    'sections'        => $this->_sections,
                    'cip_time_format' => $this->_time_format,
                ),
                true
            );
        } else {
            // Load the view from system/views
            $orig_view_path = $this->CI->load->_ci_view_path;
            $this->CI->load->_ci_view_path = BASEPATH . 'views/';

            $output = $this->CI->load->_ci_load(
                array(
                    '_ci_view'   => 'profiler_template',
                    '_ci_vars'   => array(
                        'sections'        => $this->_sections,
                        'cip_time_format' => $this->_time_format
                    ),
                    '_ci_return' => true,
                )
            );

            $this->CI->load->_ci_view_path = $orig_view_path;
        }

        return $output;
    }
}
/* End of file ./application/libraries/Profiler.php */
