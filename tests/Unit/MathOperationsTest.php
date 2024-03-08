<?php
declare(strict_types=1);

namespace Unit;

use App\Services\MathOperations\Operations\Divide;
use App\Services\MathOperations\Operations\Subtract;
use App\Services\MathOperations\Operations\Sum;
use PHPUnit\Framework\TestCase;
use App\Services\MathOperations\PrimeNumberGenerator;
use App\Services\MathOperations\Operations\Multiply;
use App\Services\MathOperations\TableGenerator;

class MathOperationsTest extends TestCase
{
    /**
     * @return void
     */
    public function testTableGenerationWithVariousOperations(): void
    {
        $numberOfPrimes = 10;

        $primeNumberGenerator = new PrimeNumberGenerator();
        $primes = $primeNumberGenerator->generatePrimes($numberOfPrimes);

        $operations = [
            'multiply' => new Multiply(),
            'divide' => new Divide(),
            'sum' => new Sum(),
            'subtract' => new Subtract()
        ];

        foreach ($operations as $operationHandler) {
            $tableGenerator = new TableGenerator();
            $resultTable = $tableGenerator->generateTable($primes, $operationHandler);

            $this->assertNotEmpty($resultTable, 'Result table should not be empty');
            $this->assertCount(
                $numberOfPrimes,
                $resultTable,
                "Table should have $numberOfPrimes rows for $numberOfPrimes primes"
            );
            foreach ($resultTable as $row) {
                $this->assertCount(
                    $numberOfPrimes,
                    $row,
                    "Each row of the table should have $numberOfPrimes columns for $numberOfPrimes primes"
                );
            }
        }
    }

    /**
     * @return void
     */
    public function testMathOperationsResults(): void
    {
        $numberOfPrimes = 5;

        $primeNumberGenerator = new PrimeNumberGenerator();
        //[2, 3, 5, 7, 11]
        $primes = $primeNumberGenerator->generatePrimes($numberOfPrimes);

        $operations = [
            'multiply' => new Multiply(),
            'divide' => new Divide(),
            'sum' => new Sum(),
            'subtract' => new Subtract()
        ];

        $expectedResult = [
            'multiply' => [
                [4, 6, 10, 14, 22],
                [6, 9, 15, 21, 33],
                [10, 15, 25, 35, 55],
                [14, 21, 35, 49, 77],
                [22, 33, 55, 77, 121]
            ],
            'divide' => [
                [1, 0.667, 0.4, 0.286, 0.182],
                [1.5, 1, 0.6, 0.429, 0.273],
                [2.5, 1.667, 1, 0.714, 0.455],
                [3.5, 2.333, 1.4, 1, 0.636],
                [5.5, 3.667, 2.2, 1.571, 1]
            ],
            'sum' => [
                [4, 5, 7, 9, 13],
                [5, 6, 8, 10, 14],
                [7, 8, 10, 12, 16],
                [9, 10, 12, 14, 18],
                [13, 14, 16, 18, 22],
            ],
            'subtract' => [
                [0, -1, -3, -5, -9],
                [1, 0, -2, -4, -8],
                [3, 2, 0, -2, -6],
                [5, 4, 2, 0, -4],
                [9, 8, 6, 4, 0],
            ]
        ];

        foreach ($operations as $operation => $operationHandler) {
            $tableGenerator = new TableGenerator();
            $resultTable = $tableGenerator->generateTable($primes, $operationHandler);
            $this->assertEquals($resultTable, $expectedResult[$operation]);
        }
    }
}
