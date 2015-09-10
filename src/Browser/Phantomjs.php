<?php

namespace BrowserStart\Browser;

use BrowserStart\BrowserAbstract;

class Phantomjs extends BrowserAbstract {

    protected function setExecPath() {
        return 'phantomjs';
    }

    protected function getCommand($port) {
        return $this->getExecPath() . sprintf(' --webdriver=localhost:%s  ', $port) . $this->getExtraParams();
    }

    protected function getURL($port, $path = '') {
        return parent::getURL($port, '');
    }

}
