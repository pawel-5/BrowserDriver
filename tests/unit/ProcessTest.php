<?php

namespace BrowserStart;

class ProcessTest extends \PHPUnit_Framework_TestCase {

    protected $process;
    protected $outputValue = "some output";

    protected function setUp() {
        $this->process = $this->getMockBuilder("BrowserStart\Process")->getMock();
        $this->process->expects($this->any())->
                method('start')->will($this->returnValue(true));
        $this->process->expects($this->any())->
                method('stop')->will($this->returnValue(true));
        $this->process->expects($this->any())->
                method('getOutput')->will($this->returnValue($this->outputValue));
    }

    public function testStart() {

        $this->assertTrue(
                $this->process->start($cmd = "foo")
        );
    }

    public function testStop() {

        $this->assertTrue(
                $this->process->stop()
        );
    }

    public function testGetOutput() {

        $this->assertSame(
                $this->outputValue, $this->process->getOutput()
        );
    }

}
