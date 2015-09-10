<?php

namespace BrowserStart\Browser;

use BrowserStart\BrowserAbstract;

class Chrome extends BrowserAbstract {

    protected function setExecPath() {
        return 'chromedriver';
    }

    protected function getCommand($port) {
        return $this->getExecPath() . sprintf(' --port=%s  ', $port) . $this->getExtraParams();
    }

    public function closeBrowser() {
        $this->checkRemote($this->getRemoteURL() . 'shutdown');
    }

}
