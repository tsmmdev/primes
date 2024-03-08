<?php
declare(strict_types=1);

namespace App\Helpers;

class FileHelper
{
    /**
     * Get file type from filename
     *
     * @param string $string
     *
     * @return string
     */
    public static function getFileType(string $string): string
    {
        $result = '';
        if (stristr($string, '.')) {
            $stringArray = explode(".", $string);
            if (count($stringArray) > 1) {
                $lastElement = end($stringArray);
                if (strlen($lastElement) <= 4) {
                    return $lastElement;
                }
            }
        } else {
            $result = $string;
        }
        return $result;
    }
}
