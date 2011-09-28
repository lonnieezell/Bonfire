<?php

if (!function_exists('estimate_lines_in_file'))
{
    
    /**
     * Estimate Lines In File
     * 
     * Counts to x number of rows and takes an average byte length and using
     * the total file size / average byte length estimates number of lines.
     * 
     * @param string $file_location a full qualifed path to file
     * @param integer $count number of lines to use in average calculation
     * @return integer estimed number of lines
     */
    function estimate_lines_in_file($file_location,$count = 100) {
       $lines  = 0; $lineSize = array();
       $orgCount = $count;
            
       
       //get the file size
       $filesize = filesize($file_location);
          
       //check for empty file
       if($filesize === 0) {
           return 0;
       }
       
       //open the file
       $openedFile = fopen($file_location,'r');
          
       
       while(!feof($openedFile) && $count > 0) {
           $line =  fgets($openedFile);
           $lineSize[] = mb_strlen($line ,'UTF-8');
           $count = $count -1;
       }
       
       //if our file is less than count lets use the accurate figure
       if($count > 0) {
           return $orgCount - $count;
       }
       
       
       $lines =  floor($filesize / floor( array_sum($lineSize) / ($orgCount - $count)));
       
       //where done with file , close it
       fclose($openedFile);
       
       return $lines;
        
        
    }
    
        
    

}
/* End of file */
