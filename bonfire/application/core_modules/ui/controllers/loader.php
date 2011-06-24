<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * loader.php
 */
 
//load_class('CSSMin', 'libraries', FALSE);
//load_class('JSMin', 'libraries', FALSE);
 
class Loader extends Front_Controller {
 
    var $asset_output;
    var $type;
    var $files;
    var $asset_path;
    var $ext;
    var $content_type;
    var $modified_time;
 
    function __construct() 
	{
		parent::__construct();
        $this->modified_time = 0;
    }
 
    function js($files)
    {
        $this->type = 'js';
        $this->files = $files;
        $this->asset_path = $this->config->item('js_path');
        $this->ext = '.js';
        $this->content_type = 'text/javascript';

		$this->_display();
    }
 
    function css($files)
    {
        $this->type = 'css';
        $this->files = $files;
        $this->asset_path = $this->config->item('css_path');
        $this->ext = '.css';
        $this->content_type = 'text/css';
 
        $this->_display();
    }
 
    private function _display()
    {
        $files_array = explode("~", $this->files);

		foreach ($files_array as $key => $file)
        {
            //replace chars for folder separation, replace ~ with /
            $file = str_replace("-", "/", $file);
			
			// if the actual asset file is not specified then assume the file is named as - TYPE.php
			if (count(explode('/', $file)) == 2)
			{
				$file .= '/'.$this->type;
			}

			$file_output = $this->load->view($file, null, TRUE);
			
			if (!empty($file_output))
			{
				$this->asset_output .= $file_output."\n";
				
				$mod_file = Modules::find($file, '', 'views/');
				
				$this->modified_time = max(filemtime($mod_file[0].$mod_file[1].'.php'), $this->modified_time);
			}
        }
 
        switch ($this->type)
        {
            case 'js':
				if (config_item('assets.js_minify'))
				{
					$this->asset_output = JSMin::minify($this->asset_output);
				}
                break;
            case 'css':
				if (config_item('assets.css_minify'))
				{
					$this->asset_output = CSSMin::minify($this->asset_output);
				}
                break;
            default:
                throw new LoaderException("Unknown file type.");
                break;
        }
 
        //gzip
        if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && config_item('assets.encode')) {
            if (stristr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
                $this->asset_output = gzencode($this->asset_output);
                header('Content-encoding: gzip');
            } else if (stristr($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate') !== false) {
                $this->asset_output = gzdeflate($this->asset_output);
                header('Content-encoding: deflate');
            }
        }
 
        //headers
        header('Content-type: ' . $this->content_type);
        header('Last-modified: ' . date('r', $this->modified_time));
        header('Expires: ' . date('r', time() + 2592000));
        header('Content-length: ' . strlen($this->asset_output));

        echo $this->asset_output;
    }
}
 
/* End of file loader.php */
/* Location: ./system/application/controllers/loader.php */