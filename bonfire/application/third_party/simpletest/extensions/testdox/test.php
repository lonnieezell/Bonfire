<?php
// $Id: test.php 1641 2008-01-22 20:13:52Z pp11 $
require_once dirname(__FILE__) . '/../../autorun.php';
require_once dirname(__FILE__) . '/../testdox.php';

// uncomment to see test dox in action
//SimpleTest::prefer(new TestDoxReporter());

class TestOfTestDoxReporter extends UnitTestCase
{
    function testIsAnInstanceOfSimpleScorerAndReporter() {
        $dox = new TestDoxReporter();
        $this->assertIsA($dox, 'SimpleScorer');
        $this->assertIsA($dox, 'SimpleReporter');
    }

    function testOutputsNameOfTestCase() {
        $dox = new TestDoxReporter();
        ob_start();
        $dox->paintCaseStart('TestOfTestDoxReporter');
        $buffer = ob_get_clean();
        $this->assertWantedPattern('/^TestDoxReporter/', $buffer);
    }

    function testOutputOfTestCaseNameFilteredByConstructParameter() {
        $dox = new TestDoxReporter('/^(.*)Test$/');
        ob_start();
        $dox->paintCaseStart('SomeGreatWidgetTest');
        $buffer = ob_get_clean();
        $this->assertWantedPattern('/^SomeGreatWidget/', $buffer);
    }

    function testIfTest_case_patternIsEmptyAssumeEverythingMatches() {
        $dox = new TestDoxReporter('');
        ob_start();
        $dox->paintCaseStart('TestOfTestDoxReporter');
        $buffer = ob_get_clean();
        $this->assertWantedPattern('/^TestOfTestDoxReporter/', $buffer);
    }

    function testEmptyLineInsertedWhenCaseEnds() {
        $dox = new TestDoxReporter();
        ob_start();
        $dox->paintCaseEnd('TestOfTestDoxReporter');
        $buffer = ob_get_clean();
        $this->assertEqual("\n", $buffer);
    }

    function testPaintsTestMethodInTestDoxFormat() {
        $dox = new TestDoxReporter();
        ob_start();
        $dox->paintMethodStart('testSomeGreatTestCase');
        $buffer = ob_get_clean();
        $this->assertEqual("- some great test case", $buffer);
        unset($buffer);

        $random = rand(100, 200);
        ob_start();
        $dox->paintMethodStart("testRandomNumberIs{$random}");
        $buffer = ob_get_clean();
        $this->assertEqual("- random number is {$random}", $buffer);
    }

    function testDoesNotOutputAnythingOnNoneTestMethods() {
        $dox = new TestDoxReporter();
        ob_start();
        $dox->paintMethodStart('nonMatchingMethod');
        $buffer = ob_get_clean();
        $this->assertEqual('', $buffer);
    }

    function testPaintMethodAddLineBreak() {
        $dox = new TestDoxReporter();
        ob_start();
        $dox->paintMethodEnd('someMethod');
        $buffer = ob_get_clean();
        $this->assertEqual("\n", $buffer);
        $this->assertNoErrors();
    }

    function testProperlySpacesSingleLettersInMethodName() {
        $dox = new TestDoxReporter();
        ob_start();
        $dox->paintMethodStart('testAVerySimpleAgainAVerySimpleMethod');
        $buffer = ob_get_clean();
        $this->assertEqual('- a very simple again a very simple method', $buffer);
    }

    function testOnFailureThisPrintsFailureNotice() {
        $dox = new TestDoxReporter();
        ob_start();
        $dox->paintFail();
        $buffer = ob_get_clean();
        $this->assertEqual(' [FAILED]', $buffer);
    }

    function testWhenMatchingMethodNamesTestPrefixIsCaseInsensitive() {
        $dox = new TestDoxReporter();
        ob_start();
        $dox->paintMethodStart('TESTSupportsAllUppercaseTestPrefixEvenThoughIDoNotKnowWhyYouWouldDoThat');
        $buffer = ob_get_clean();
        $this->assertEqual(
            '- supports all uppercase test prefix even though i do not know why you would do that',
            $buffer
        );
    }
}

