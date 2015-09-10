<?php

namespace BrowserStart;

class Process {

    protected $pipes = null;
    protected $cmdHandler = null;

    public function start($cmd) {
        $descriptors = array(
            0 => array('pipe', 'r'), // stdin
            1 => array('pipe', 'w'), // stdout
            2 => array('pipe', 'a'), // stderr
        );

        $this->cmdHandler = proc_open($cmd, $descriptors, $this->pipes);

        if (!is_resource($this->cmdHandler)) {
            throw new Exception('Can not start process');
        }
        return true;
    }

    public function stop() {
        $s = proc_get_status($this->cmdHandler);

        fclose($this->pipes[0]);
        fclose($this->pipes[1]);
        fclose($this->pipes[2]);

        $pidCmd = 'ps -o pid --no-headers --ppid ' . $s['pid'];
        $cmdOutput = shell_exec($pidCmd);

        proc_terminate($this->cmdHandler);

        if (preg_match_all("~(?<PIDS>\d+)~m", $cmdOutput, $pids)) {
            foreach ($pids['PIDS'] as $pid) {
                posix_kill((int) $pid, SIGTERM);
            }
        }

        return true;
    }

    public function getOutput() {

        if ($this->cmdHandler) {
            $driver_logs = $this->readFromPipe($this->pipes[1]);
            $driver_logs .= $this->readFromPipe($this->pipes[2]);

            return $driver_logs;
        } else {
            throw new Exception('Process pipes are closed nothing to read ');
        }
    }

    protected function readFromPipe($pipe) {
        stream_set_blocking($pipe, 0);
        $r = '';
        $check = false;
        while (!feof($pipe)) {
            $r .= fgets($pipe);
            $info = stream_get_meta_data($pipe);
            if ($check && $info['unread_bytes'] == 0) {
                break;
            }
            $check = true;
        }

        return $r;
    }

}
