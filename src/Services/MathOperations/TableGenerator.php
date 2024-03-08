<?php
declare(strict_types=1);

namespace App\Services\MathOperations;

use App\Services\MathOperations\Interfaces\MathOperationInterface;

class TableGenerator
{
    /**
     * Generates tables with produce of custom math operations
     */
    public function generateTable(
        array $primeNumbers,
        mathOperationInterface $mathOperationHandler
    ): array {
        $result = [];
        $y = 0;
        foreach ($primeNumbers as $primeYValue) {
            $x = 0;
            foreach ($primeNumbers as $primeXvalue) {
                $result[$y][$x] = $mathOperationHandler->evaluate($primeYValue, $primeXvalue);
                $x++;
            }
            $y++;
        }
        return $result;
    }
}
