<?php
declare(strict_types=1);

namespace App\Services\MathOperations\Operations;

use App\Services\MathOperations\Interfaces\MathOperationInterface;

class Subtract implements MathOperationInterface
{
    /**
     * Evaluates subtraction
     *
     * @param int $a
     * @param int $b
     *
     * @return int
     */
    public function evaluate(int $a, int $b): int
    {
        return $a - $b;
    }
}
