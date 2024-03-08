<?php
declare(strict_types=1);

namespace App\Helpers;

class MessageHelper
{
    /**
     * @param string $message
     * @param bool $terminate
     *
     * @return void
     */
    public static function finalMessage(
        string $message,
        bool $terminate = true
    ): void {
        echo PHP_EOL . $message . PHP_EOL . PHP_EOL;
        if ($terminate) {
            exit;
        }
    }
}
