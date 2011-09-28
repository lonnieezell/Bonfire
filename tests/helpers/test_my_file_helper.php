<?php
class test_my_file_helper extends CodeIgniterUnitTestCase {

	protected $file = '';

	//--------------------------------------------------------------------

	public function __construct() 
	{
		parent::__construct();
		
		$this->load->helper('file');
	}
        
        
        public function setup() {
      
            //make a temple file with nonsence data
            
            $this->file = __DIR__ . DIRECTORY_SEPARATOR . 'tempfile.txt';
            touch($this->file);
            $openedFile = fopen($this->file,'w');
          
            for($i = 1000; $i > 0; --$i) {
                fwrite($openedFile, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaadddddddddddddd'. PHP_EOL);
            }
       
            fclose($openedFile);
            
            
        }
        
        public function tearDown() {
            $file = $this->file;
            
            if(is_file($file) === TRUE) {
                unlink($file);
            }
        }
        
        public function test_estimate_lines_in_file_small() {
            $file = __FILE__;
            $result = estimate_lines_in_file($file,100);
            
            $this->assertTrue($result > 0,'Files size has made an estimate of '.$result);
            
        }
        
        public function test_estimate_lines_in_file_large() {
            $result = estimate_lines_in_file($this->file);
            $this->assertEqual(1000,$result,'Files size has made a good estimate ' .$result);
        }
        
}