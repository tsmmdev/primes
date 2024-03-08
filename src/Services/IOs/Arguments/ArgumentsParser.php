<?php
declare(strict_types=1);

namespace App\Services\IOs\Arguments;

class ArgumentsParser
{
    /**
     * Parses command-line arguments into a structured array.
     *
     * @param array $arguments
     *
     * @return array
     */
    public function parse(array $arguments): array
    {
        $options = [];
        foreach ($arguments as $arg) {
            if (substr($arg, 0, 1) === '-') {
                $currentOption = substr($arg, 1);
                $currentOptionArray = explode('=', $currentOption);
                $options[$currentOptionArray[0]] = $currentOptionArray[1] ?? '';
            }
        }
        return $options;
    }
}
