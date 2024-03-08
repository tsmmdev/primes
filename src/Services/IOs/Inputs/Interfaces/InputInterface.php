<?php

namespace App\Services\IOs\Inputs\Interfaces;

interface InputInterface
{
    /**
     * Process to output
     *
     * @param string $source
     *
     * @return array
     */
    public function getArguments(string $source): array;
}
