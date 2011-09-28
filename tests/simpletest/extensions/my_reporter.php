<?php
/**
 *    Recipient of generated test messages that can display
 *    page footers and headers. Also keeps track of the
 *    test nesting. This is the main base class on which
 *    to build the finished test (page based) displays.
 *    @package SimpleTest
 *    @subpackage UnitTester
 */
class MyReporter extends SimpleReporter {

	protected $_charset = 'utf-8';

	public function __construct($character_set='utf-8')
	{
		 parent::__construct();
		 $this->_character_set = $character_set;

	}

    /**
     *    Paints the start of a group test. Will also paint
     *    the page header and footer if this is the
     *    first test. Will stash the size if the first
     *    start.
     *    @param string $test_name   Name of test that is starting.
     *    @param integer $size       Number of test cases starting.
     *    @access public
     */
    public function paintGroupStart($test_name, $size)
    {
        echo '<div class="test-group">';
        parent::paintGroupStart($test_name, $size);
    }

    /**
     *    Paints the end of a group test. Will paint the page
     *    footer if the stack of tests has unwound.
     *    @param string $test_name   Name of test that is ending.
     *    @param integer $progress   Number of test cases ending.
     *    @access public
     */
    public function paintGroupEnd($test_name)
    {
    	echo '</div>';
    	parent::paintGroupEnd($test_name);
        //echo '</h3>Test Suite: "' . $test_name . '" finished.</h3><br />';
    	//flush();
    }

    /**
     *    Paints the start of a test case. Will also paint
     *    the page header and footer if this is the
     *    first test. Will stash the size if the first
     *    start.
     *    @param string $test_name   Name of test that is starting.
     *    @access public
     */
    public function paintCaseStart($test_name)
    {
        parent::paintCaseStart($test_name);
		echo '<h2>' . $test_name . '</h2>';
		echo '<div class="test-reports">';
        
    	flush();
    }

    /**
     *    Paints the end of a test case. Will paint the page
     *    footer if the stack of tests has unwound.
     *    @param string $test_name   Name of test that is ending.
     *    @access public
     */
    public function paintCaseEnd($test_name)
    {
        parent::paintCaseEnd($test_name);
        //echo '<strong>Test Case: "' . $test_name . '" finished.</strong><br />';
		echo '</div>';
    	flush();
    }

    /**
     *    Paints the start of a test method.
     *    @param string $test_name   Name of test that is starting.
     *    @access public
     */
    public function paintMethodStart($test_name)
    {
        parent::paintMethodStart($test_name);
        //echo '<h3>' . $test_name . '</h3>';
    	flush();
    }

    /**
     *    Paints the end of a test method. Will paint the page
     *    footer if the stack of tests has unwound.
     *    @param string $test_name   Name of test that is ending.
     *    @access public
     */
    public function paintMethodEnd($test_name)
    {
        parent::paintMethodEnd($test_name);
        //echo '<em>Test: "' . $test_name . '" finished.</em><br />';
    	flush();
    }

    /**
     *    Paints the top of the web page setting the
     *    title to the name of the starting test.
     *    @param string $test_name      Name class of test.
     *    @access public
     */
    public function paintHeader($test_name)
    {
        $this->sendNoCacheHeaders();
        /*
        print "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
        print "<html>\n<head>\n<title>$test_name</title>\n";
        print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" .
                $this->_character_set . "\">\n";
       */
		print "<style type=\"text/css\">\n";
        print $this->_getCss() . "\n";
        print "</style>\n";
        //print "</head>\n<body>\n";
        print "<h1>$test_name</h1>\n";
        flush();
    }

    /**
     *    Send the headers necessary to ensure the page is
     *    reloaded on every request. Otherwise you could be
     *    scratching your head over out of date test data.
     *    @access public
     *    @static
     */
    function sendNoCacheHeaders()
    {
        if (! headers_sent()) {
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
        }
    }

    /**
     *    Paints the CSS. Add additional styles here.
     *    @return string            CSS code as text.
     *    @access protected
     */
    function _getCss()
    {
        //return ".fail { background-color: inherit; color: red; }" .
        //       ".pass { background-color: inherit; color: green; }" .
        //        " pre { background-color: lightgray; color: inherit; }";
        flush();
    }

    /**
     *    Paints the end of the test with a summary of
     *    the passes and failures.
     *    @param string $test_name        Name class of test.
     *    @access public
     */
    function paintFooter($test_name)
    {
        $colour = ($this->getFailCount() + $this->getExceptionCount() > 0 ? "fail" : "pass");
        print "<div class=\"summary $colour\">";
        print $this->getTestCaseProgress() . "/" . $this->getTestCaseCount();
        print " test cases complete:\n";
        print "<strong>" . $this->getPassCount() . "</strong> passes, ";
        print "<strong>" . $this->getFailCount() . "</strong> fails and ";
        print "<strong>" . $this->getExceptionCount() . "</strong> exceptions.";
        print "</div>\n";
        //print "</body>\n</html>\n";
        flush();
    }
	
	function paintPass($message) 
	{
		parent::paintPass($message);
		$breadcrumb = $this->getTestList();
		$test = array_pop($breadcrumb);
		echo '<div class="test pass">
			<div class="result">PASSED</div>
			<h3>'.$test.'</h3>
			<div class="details">
				<em>'.$message.'</em>
			</div>
		</div>
		';
	}
	
    
    /**
     *    Paints the test failure with a breadcrumbs
     *    trail of the nesting test suites below the
     *    top level test.
     *    @param string $message    Failure message displayed in
     *                              the context of the other tests.
     *    @access public
     */
    function paintFail($message)
    {
        parent::paintFail($message);
		$breadcrumb = $this->getTestList();
		$test = array_pop($breadcrumb);
		echo '<div class="test fail">
			<div class="result">FAILED</div>
			<h3>'.$test.'</h3>
			<div class="details">
				<em>'.$message.'</em>
			</div>
		</div>
		';
    }

    /**
     *    Paints a PHP error.
     *    @param string $message        Message is ignored.
     *    @access public
     */
    function paintError($message)
    {
        parent::paintError($message);
        print "<span class=\"fail\">Exception</span>: ";
        $breadcrumb = $this->getTestList();
        array_shift($breadcrumb);
        print implode(" -&gt; ", $breadcrumb);
        print " -&gt; <strong>" . $this->_htmlEntities($message) . "</strong><br />\n";
        flush();
    }

    /**
     *    Paints a PHP exception.
     *    @param Exception $exception        Exception to display.
     *    @access public
     */
    function paintException($exception)
    {
        parent::paintException($exception);
        print "<span class=\"fail\">Exception</span>: ";
        $breadcrumb = $this->getTestList();
        array_shift($breadcrumb);
        print implode(" -&gt; ", $breadcrumb);
        $message = 'Unexpected exception of type [' . get_class($exception) .
                '] with message ['. $exception->getMessage() .
                '] in ['. $exception->getFile() .
                ' line ' . $exception->getLine() . ']';
        print " -&gt; <strong>" . $this->_htmlEntities($message) . "</strong><br />\n";
        flush();
    }

    /**
     *    Prints the message for skipping tests.
     *    @param string $message    Text of skip condition.
     *    @access public
     */
    function paintSkip($message)
    {
        parent::paintSkip($message);
        print "<span class=\"pass\">Skipped</span>: ";
        $breadcrumb = $this->getTestList();
        array_shift($breadcrumb);
        print implode(" -&gt; ", $breadcrumb);
        print " -&gt; " . $this->_htmlEntities($message) . "<br />\n";
        flush();
    }

    /**
     *    Paints formatted text such as dumped variables.
     *    @param string $message        Text to show.
     *    @access public
     */
    function paintFormattedMessage($message)
    {
        print '<pre class="message">' . $this->_htmlEntities($message) . '</pre>';
        flush();
    }

    /**
     *    Character set adjusted entity conversion.
     *    @param string $message    Plain text or Unicode message.
     *    @return string            Browser readable message.
     *    @access protected
     */
    function _htmlEntities($message)
    {
        return htmlentities($message, ENT_COMPAT, $this->_character_set);
        flush();
    }
}
