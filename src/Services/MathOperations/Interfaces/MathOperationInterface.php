<?php
declare(strict_types=1);

namespace App\Services\MathOperations\Interfaces;

interface MathOperationInterface
{
    /**
     * Evaluates math operation
     *
     * @param int $a
     * @param int $b
     *
     * @return float|int
     */
    public function evaluate(int $a, int $b);
}
