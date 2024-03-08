<?php
declare(strict_types=1);

namespace EndToEnd;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PrimeMultiplicationTableE2ETest extends TestCase
{
    protected static string $scriptPath;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        self::$scriptPath = realpath(__DIR__ . '/../../app.php');
    }

    /**
     * @return void
     */
    public function testApplicationOutputsCorrectMultiplicationTable(): void
    {

        $numberOfPrimes = 5;
        $command = ['php', self::$scriptPath, "-n=$numberOfPrimes", '-operation=*'];

        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $expectedResult =
            " * | 2 | 3 | 5 | 7 | 11" . PHP_EOL .
            "2 | 4 | 6 | 10 | 14 | 22" . PHP_EOL .
            "3 | 6 | 9 | 15 | 21 | 33" . PHP_EOL .
            "5 | 10 | 15 | 25 | 35 | 55" . PHP_EOL .
            "7 | 14 | 21 | 35 | 49 | 77" . PHP_EOL .
            "11 | 22 | 33 | 55 | 77 | 121" . PHP_EOL;

        $output = $process->getOutput();
        $this->assertEquals($expectedResult, $output);

//        $this->assertStringContainsString("2 | 4 | 6 | 10 | 14 | 22", $output, 'The table should contain the product of 2 and 3.');
    }

    /**
     * @return void
     */
    public function testApplicationOutputsInvalidOperation(): void
    {

        $numberOfPrimes = 5;
        $command = ['php', self::$scriptPath, "-n=$numberOfPrimes", '-operation=non-existing'];

        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $expectedResult = PHP_EOL . "Invalid or unsupported math operation 'non-existing'!" . PHP_EOL . PHP_EOL;
        $output = $process->getOutput();

        $this->assertEquals($expectedResult, $output);
    }
}
