<?php
declare(strict_types=1);

namespace App\Services\IOs\Outputs\Handlers;

use App\Services\IOs\Outputs\Interfaces\OutputInterface;

class ToStdout implements OutputInterface
{
    /**
     * Process to output
     *
     * @param string $destination
     * @param array $primes
     * @param array $table
     *
     * @return bool
     */
    public function doOutput(
        string $destination,
        array $primes,
        array $table
    ): bool {
        $result = '';
        if (!empty($table)) {
            $result = " * | ";
            $result .= implode(" | ", $primes) . PHP_EOL;
            foreach ($table as $keyY => $arrayX) {
                $result .= $primes[$keyY] . " | ";
                $result .= implode(" | ", $arrayX) . PHP_EOL;
            }
        }
        echo $result;
        return true;
    }
}
