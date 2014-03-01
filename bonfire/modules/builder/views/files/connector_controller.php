<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


$controller = <<<END
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * connector controller
 */
class connector extends Admin_Controller {

    //--------------------------------------------------------------------

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct() {
	parent::__construct();
    }


    //--------------------------------------------------------------------

   /**
    * Locate your uploads directory where you want and manage your storage policy as you need modifying this method.
    * Set access permission to this method in Bonfire administration area.
    * For secutity reasons, it's recommended to place upload folder outside from the web root (but images previews are not available).
    * If you want to assing a distinct folder to each user, you might to use the user id value: \$this->current_user->id
    */
    public function index() {

	\$user_dir = \$_SERVER['DOCUMENT_ROOT'] . '/my_uploads_folder/' . \$this->current_user->id . '/';

	if (!is_dir(\$user_dir)) {
	    @mkdir(\$user_dir, 0777);
	    //file_put_contents(\$user_dir . "/index.html", "Access denied!"); //index file to avoid directory listing (OPTIONAL)
	}

	\$opts = array(
	    // 'debug' => true,
	    'roots' => array(
		array(
		    'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
		    'path' => \$user_dir, // path to files, set as you want (REQUIRED)
		    'URL' => '/../my_uploads_folder/' . \$this->current_user->id . '/', // URL to files, set as you want (REQUIRED)
		    'accessControl' => 'access', // disable and hide dot starting files (OPTIONAL)
		    'alias' => ucfirst(\$this->current_user->username) . ' Home',
		    'tmbPath' => '.thumbs', // directory name for image thumbnails. Set to "" to avoid thumbnails generation
		    //'tmbUrl' => '/my_uploads_folder/' . \$this->current_user->id . '/.thumbs/', // thumbnails directory url. Set only if the directory is outside from web root.
		    'dateFormat' => 'd/m/Y H:i', // file modification date format
		    'dotFiles' => true, // display dot files
		    'dirSize' => true, // count total directories sizes
		    'fileMode' => 0666, // new files mode
		    'dirMode' => 0777, // new folders mode
		    //'disabled'     => array(),	// list of not allowed commands
		    'imgLib' => 'auto', // image manipulation library (imagick, mogrify, gd)
		    // 'tmbCleanProb' => 1,		// how frequiently clean thumbnails dir (0 - never, 100 - every init request)
		    // 'tmbAtOnce'    => 5,		// number of thumbnails to generate per request
		    'tmbSize' => 48, // images thumbnails size (px)
		    'fileURL' => true, // display file URL in "get info"
		    //FILE TYPES (For all mime types, see mime.types file in ElFinder library directory)
		    'mimeDetect' => 'auto', // files mimetypes detection method (finfo, mime_content_type, linux (file -ib), bsd (file -Ib), internal (by extensions))
		    //'uploadAllow' => array('images/*', 'application/pdf'),
		    'uploadDeny' => array('application/x-executable'),
		//'uploadOrder'   => 'deny,allow'
		)
	    )
	);

	// Run ElFinder library
	\$this->load->library('ElFinder/ElFinderLib', \$opts);
    }


    //end connector
}
//end class
END;

echo $controller;
?>
