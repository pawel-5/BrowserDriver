<?php

namespace BrowserStart;

class BrowserAbstractTest extends \PHPUnit_Framework_TestCase {

    protected $outputValue = "some output";
    protected $browserAbstract;

    protected function setUp() {

        $process = $this->getMock("\BrowserStart\Process");
        $process->expects($this->any())->method('getOutput')->will($this->returnValue($this->outputValue));

        $this->browserAbstract = $this->getMockForAbstractClass(
                "BrowserStart\BrowserAbstract", $arguments = array($process), 
                $mockClassName = '', $callOriginalConstructor = TRUE, $callOriginalClone = TRUE, 
                $callAutoload = TRUE, $mockedMethods = array("checkIfStarted"), $cloneArguments = FALSE
                );
        $this->browserAbstract->expects($this->any())->method('checkIfStarted')->will($this->returnValue(null));
    }

    public function testStartBrowser() {
        $this->assertRegExp(
                "~http://localhost:\d+/~", $this->browserAbstract->startBrowser()
        );
    }

    public function testCloseBrowser() {
        $this->assertTrue(
                $this->browserAbstract->closeBrowser()
        );
    }

    public function testGetDriverLogs() {
        $this->assertSame(
                $this->outputValue, $this->browserAbstract->getDriverLogs()
        );
    }

}
