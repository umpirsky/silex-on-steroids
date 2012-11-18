<?php

namespace Sos\Composer;

use Composer\Script\Event;

class Script
{
    public static function postInstall(Event $event)
    {
        self::mkdir('resources/cache', 0777);
        self::mkdir('resources/log', 0777);
        self::mkdir('web/assets', 0777);
        chmod('console', 0500);
        exec('php console assetic:dump');
    }

    private static function mkdir($path, $mode)
    {
        if (is_dir($path)) {
            return;
        }

        mkdir($path, $mode);
    }
}
