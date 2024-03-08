<?php
declare(strict_types=1);

namespace Integration;

use App\Services\IOs\Outputs\Handlers\ToStdout;
use Exception;
use PHPUnit\Framework\TestCase;

class ToStdoutTest extends TestCase
{
    /**
     * @return void
     */
    public function testToStdout(): void
    {
        $outputHandler = new ToStdout();

        $primes = [2, 3, 5];
        $table = [
            [4, 6, 10],
            [6, 9, 15],
            [10, 15, 25],
        ];

        ob_start();
        $outputHandler->doOutput('stdout', $primes, $table);
        // Clean up
        $output = ob_get_clean();

        $expectedContent = " * | 2 | 3 | 5\n2 | 4 | 6 | 10\n3 | 6 | 9 | 15\n5 | 10 | 15 | 25\n";
        $this->assertEquals($expectedContent, $output);
    }

    /**
     * @return void
     */
    public function testToStdoutWithTrashData(): void
    {
        $outputHandler = new ToStdout();

        $primes = [2, 'a', 'b', 'hm'];
        $table = [
            [4, -6, 10],
            [6, 9, 15, 'dsf'],
            [10, 'wtf', 25],
        ];

        // Start output buffering
        ob_start();
        $outputHandler->doOutput('stdout', $primes, $table);

        // Capture and clean the output buffer
        $output = ob_get_clean();

        $expectedContent = " * | 2 | a | b | hm\n2 | 4 | -6 | 10\na | 6 | 9 | 15 | dsf\nb | 10 | wtf | 25\n";
        $this->assertEquals($expectedContent, $output);
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testToStdoutWithShorterPrimesThanTableSideWithExpectedUnhandledError(): void
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

        $outputHandler = new ToStdout();

        $primes = ['hm', 'df'];
        $table = [
            [4, -6, 10],
            [6, 9, 15, 'dsf'],
            [10, 'wtf', 25],
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Undefined array key');

        ob_start();

        try {
            // Attempt to generate output, expecting error handling to kick in
            $outputHandler->doOutput('stdout', $primes, $table);
        } finally {
            ob_end_clean();
            restore_error_handler();
        }
    }

    /**
     * @return void
     */
    public function testToStdoutWithEmptyData(): void
    {
        $outputHandler = new ToStdout();
        ob_start();
        $outputHandler->doOutput('stdout', [], []);
        $output = ob_get_clean();
        $this->assertEmpty(trim($output), 'Output should be empty for empty input data');
    }
}
