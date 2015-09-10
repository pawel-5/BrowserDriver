# BrowserDriver

PHP Wraper to start browser for https://github.com/facebook/php-webdriver (no need to run selenium-server-standalone-*.jar)

Current supported browser (Linux only):

* Chrome (with chromedriver https://code.google.com/p/selenium/wiki/ChromeDriver)

* PhantomJS (any version with embedded GhostDriver should work)

How to run: 

* Include or have in PATH https://github.com/facebook/php-webdriver sources

* Start browser,  pass remoteURL to RemoteWebDriver::create 
    ```php
    $browser = BrowserDriver::run(BrowserDriver::BROWSER_CHROME);

    $remoteURL = $browser->startBrowser();
    ```

* If executables are not in path: 
    ```php
    $browser = BrowserDriver::run( BrowserDriver::BROWSER_CHROME, 
                    $exec_path = 'location for exec ', 
                    $extra_params = 'extra params for executable');

    ```

* Do your thing 

* Close browser when not needed anymore
    ```php
    $browser->closeBrowser();

    ```

For unit testing run:

    phpunit

If you have chromedriver and phantomjs in PATH you can run :
 
    phpunit tests/functional/ 
