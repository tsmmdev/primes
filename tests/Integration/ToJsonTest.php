<?php
declare(strict_types=1);

namespace Integration;

use App\Services\IOs\Outputs\Handlers\ToJson;
use Exception;
use PHPUnit\Framework\TestCase;

class ToJsonTest extends TestCase
{

    /**
     * @return void
     */
    public function testJsonOutput(): void
    {
        $outputHandler = new ToJson();
        $destinationPath = __DIR__ . '/../OutputSamples/output.json';
        $primes = [2, 3, 5];
        $table = [
            [4, 6, 10],
            [6, 9, 15],
            [10, 15, 25],
        ];

        // Generate output
        $outputHandler->doOutput($destinationPath, $primes, $table);

        // Verify output
        $this->assertFileExists($destinationPath);
        $content = file_get_contents($destinationPath);
        $decodedContent = json_decode($content, true);

        $expectedDecodedContent = [
            ['*', 2, 3, 5],
            [2, 4, 6, 10],
            [3, 6, 9, 15],
            [5, 10, 15, 25]
        ];
        $this->assertEquals($expectedDecodedContent, $decodedContent);

        // Clean up
        unlink($destinationPath);
    }

    /**
     * @return void
     */
    public function testToJsonWithTrashData(): void
    {
        $outputHandler = new ToJson();
        $destinationPath = __DIR__ . '/../OutputSamples/output.json';
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

        // Verify output
        $this->assertFileExists($destinationPath);
        $content = file_get_contents($destinationPath);
        $decodedContent = json_decode($content, true);

        $expectedDecodedContent = [
            ['*', 2, 'a', 'b', 'hm'],
            [2, 4, -6, 10],
            ['a', 6, 9, 15, 'dsf'],
            ['b', 10, 'wtf', 25]
        ];
        $this->assertEquals($expectedDecodedContent, $decodedContent);

        // Clean up
        unlink($destinationPath);
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testToJsonWithShorterPrimesThanTableSideWithExpectedUnhandledError(): void
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

        $outputHandler = new ToJson();
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

    public function testToJsonWithEmptyData(): void
    {
        $outputHandler = new ToJson();
        $destinationPath = __DIR__ . '/../OutputSamples/output.txt';
        if (file_exists($destinationPath)) {
            unlink($destinationPath);
        }
        $outputHandler->doOutput($destinationPath, [], []);
        $this->assertFileDoesNotExist($destinationPath);
    }
}
