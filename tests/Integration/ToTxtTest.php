<?php
declare(strict_types=1);

namespace Integration;

use App\Services\IOs\Outputs\Handlers\ToTxt;
use Exception;
use PHPUnit\Framework\TestCase;

class ToTxtTest extends TestCase
{
    /**
     * @return void
     */
    public function testTxtOutput(): void
    {
        $outputHandler = new ToTxt();
        $destinationPath = __DIR__ . '/../OutputSamples/output.txt';
        $primes = [2, 3, 5];
        $table = [
            [4, 6, 10],
            [6, 9, 15],
            [10, 15, 25],
        ];

        $outputHandler->doOutput($destinationPath, $primes, $table);

        $this->assertFileExists($destinationPath);
        $content = file_get_contents($destinationPath);
        $expectedContent = " * | 2 | 3 | 5\n2 | 4 | 6 | 10\n3 | 6 | 9 | 15\n5 | 10 | 15 | 25\n";
        $this->assertEquals($expectedContent, $content);

        // Clean up
        unlink($destinationPath);
    }

    /**
     * @return void
     */
    public function testToTxtWithTrashData(): void
    {
        $outputHandler = new ToTxt();
        $destinationPath = __DIR__ . '/../OutputSamples/output.txt';
        if (file_exists($destinationPath)) {
            unlink($destinationPath);
        }
        $primes = [2, 'a', 'b', 'hm'];
        $table = [
            [4, -6, 10],
            [6, 9, 15, 'dsf'],
            [10, 'wtf', 25],
        ];

        $outputHandler->doOutput($destinationPath, $primes, $table);
        $this->assertFileExists($destinationPath);
        $content = file_get_contents($destinationPath);
        $expectedContent = " * | 2 | a | b | hm\n2 | 4 | -6 | 10\na | 6 | 9 | 15 | dsf\nb | 10 | wtf | 25\n";
        $this->assertEquals($expectedContent, $content);

        // Clean up
        unlink($destinationPath);
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testToTxtWithShorterPrimesThanTableSideWithExpectedUnhandledError(): void
    {

        set_error_handler(
        /**
         * @throws Exception
         */
            function ($errno, $errorString) {
                throw new Exception($errorString, $errno);
            },
            E_WARNING
        );

        $outputHandler = new ToTxt();
        $destinationPath = 'we dont need file for this test';
        $primes = ['hm', 'df'];
        $table = [
            [4, -6, 10],
            [6, 9, 15, 'dsf'],
            [10, 'wtf', 25],
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Undefined array key');

        try {
            // Attempt to generate output, expecting error handling to kick in
            $outputHandler->doOutput($destinationPath, $primes, $table);
        } finally {
            restore_error_handler();
        }
    }

    /**
     * @return void
     */
    public function testToTxtWithEmptyData(): void
    {
        $outputHandler = new ToTxt();
        $destinationPath = __DIR__ . '/../OutputSamples/output.txt';
        if (file_exists($destinationPath)) {
            unlink($destinationPath);
        }
        $outputHandler->doOutput($destinationPath, [], []);
        $this->assertFileDoesNotExist($destinationPath);
    }
}
