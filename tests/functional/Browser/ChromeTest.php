<?php

namespace BrowserStart\Browser;

use BrowserStart\Process;

class ChromeTest extends \PHPUnit_Framework_TestCase {

    protected static $chrome;
    protected $url;

    public static function setUpBeforeClass() {

        $process = new Process();
        self::$chrome = new Chrome($process);
    }

    public function testStartBrowser() {

        $this->url = self::$chrome->startBrowser();
        $this->assertJson(
                $this->execCurl($this->url . "status")
        );
    }

    /**
     * @depends testStartBrowser
     */
    public function testGetDriverLogs() {
        $this->assertRegExp(
                "~(Started ChromeDriver)|(Only local connections are allowed)~i", 
                self::$chrome->getDriverLogs()
        );
    }

    /**
     * @depends testGetDriverLogs
     */
    public function testClose() {
        self::$chrome->closeBrowser();
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
