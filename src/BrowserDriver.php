<?php

namespace BrowserStart;

use BrowserStart\Browser\Chrome;
use BrowserStart\Browser\Phantomjs;

require_once __DIR__ . '/BrowserAbstract.php';
require_once __DIR__ . '/Browser/Phantomjs.php';
require_once __DIR__ . '/Browser/Chrome.php';
require_once __DIR__ . '/Process.php';

class BrowserDriver {

    const BROWSER_PHANTOMJS = 'PHANTOMJS';
    const BROWSER_CHROME = 'CHROME';

    public static function run($browserType, $exec_path = '', $extra_params = '') {
        switch ($browserType) {
            case self::BROWSER_PHANTOMJS:
                return new Phantomjs(new Process, $exec_path, $extra_params);
            case self::BROWSER_CHROME:
                return new Chrome(new Process, $exec_path, $extra_params);
            default:
                throw new Exception('Wrong browser type');
                break;
        }
    }

}
