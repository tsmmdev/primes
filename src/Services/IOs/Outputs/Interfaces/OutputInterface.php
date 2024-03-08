<?php

namespace App\Services\IOs\Outputs\Interfaces;

interface OutputInterface
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
    ): bool;
}
