<?php

namespace BrowserStart\Browser;

use BrowserStart\Process;

class PhantomjsTest extends \PHPUnit_Framework_TestCase {

    protected static $phantomjs;
    protected $url;

    public static function setUpBeforeClass() {

        $process = new Process();
        self::$phantomjs = new Phantomjs($process);
    }

    public function testStartBrowser() {

        $this->url = self::$phantomjs->startBrowser();
        $this->assertJson(
                $this->execCurl($this->url . "/status")
        );
    }

    /**
     * @depends testStartBrowser
     */
    public function testGetDriverLogs() {
        $this->assertContains(
                "GhostDriver - Main - running on port", self::$phantomjs->getDriverLogs()
        );
    }

    /**
     * @depends testGetDriverLogs
     */
    public function testClose() {
        self::$phantomjs->closeBrowser();
        $this->assertRegExp(
                "~Could.* resolve host~i", $this->execCurl($this->url . "status")
        );
    }

    protected function execCurl($url) {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        $ret = curl_exec($ch);
        $ret .= curl_error($ch);
        curl_close($ch);

        return $ret;
    }

}
