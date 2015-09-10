<?php

namespace BrowserStart;

abstract class BrowserAbstract {

    private $execPath = null;
    private $extraParmas = null;
    private $remoteURL = '';
    private $process = null;

    public function __construct(\BrowserStart\Process $process, $execPath = '', $extraParams = '') {

        $this->process = $process;

        if (!$execPath) {
            $execPath = $this->setExecPath();
        }

        $this->execPath = $execPath;
        $this->extraParmas = $extraParams;
    }

    abstract protected function getCommand($port);

    abstract protected function setExecPath();

    protected function getURL($port, $path = '') {

        $this->remoteURL = sprintf('http://localhost:%s/%s', $port, $path);

        return $this->remoteURL;
    }

    protected function getRemoteURL() {
        return $this->remoteURL;
    }

    protected function getFreePort() {

        for ($i = 0; $i < 5; ++$i) {
            $port = rand(40000, 60000);
            // @ is to hide warning 
            $fp = @fsockopen('127.0.0.1', $port, $errno, $errstr, 0.5);
            // false and conn refused seems to be free port 
            if (!$fp && $errno == SOCKET_ECONNREFUSED) {
                return $port;
            } elseif (!$fp) {
                // noop
            } else {
                fclose($fp);
            }
        }

        throw new Exception('Can not find free port');
    }

    public function startBrowser() {

        $port = $this->getFreePort();

        $this->getURL($port);

        $cmd = $this->getCommand($port);

        $this->process->start($cmd);

        $this->checkIfStarted();

        return $this->remoteURL;
    }

    protected function checkIfStarted() {
        for ($i = 0; $i < 10; ++$i) {
            usleep(500000);
            $sessions = $this->checkRemote($this->getRemoteURL() . 'status');
            if (json_decode($sessions)) {
                break;
            }
        }
    }

    public function closeBrowser() {

        $this->process->stop();
        return true;
    }

    public function getDriverLogs() {
        return $this->process->getOutput();
    }

    protected function getExecPath() {
        return $this->execPath;
    }

    protected function getExtraParams() {
        return $this->extraParmas;
    }

    protected function checkRemote($url, $curlParams = array()) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        if ($curlParams) {
            curl_setopt_array($ch, $curlParams);
        }
        $ret = curl_exec($ch);
        $ret .= curl_error($ch);
        curl_close($ch);

        return $ret;
    }

}
