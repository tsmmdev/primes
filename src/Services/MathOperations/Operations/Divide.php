<?php
declare(strict_types=1);

namespace App\Services\MathOperations\Operations;

use App\Services\MathOperations\Interfaces\MathOperationInterface;

class Divide implements MathOperationInterface
{
    /**
     * Evaluates division
     *
     * @param int $a
     * @param int $b
     *
     * @return float|int
     */
    public function evaluate(int $a, int $b)
    {
        $result = $a / $b;
        if (is_float($result)) {
            $result = round($result, 3);
        }
        return $result;
    }
}
