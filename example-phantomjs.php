<?php

require_once("../php-webdriver/vendor/autoload.php");

require_once("./src/BrowserDriver.php");

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use BrowserStart\BrowserDriver;

$capabilities = DesiredCapabilities::phantomjs();

$browser = BrowserDriver::run(BrowserDriver::BROWSER_PHANTOMJS);

$remoteURL = $browser->startBrowser();

$wd = RemoteWebDriver::create($remoteURL, $capabilities, 5000);

$wd->get("http://docs.seleniumhq.org");

echo $wd->getTitle() . PHP_EOL;

$browser->closeBrowser();



