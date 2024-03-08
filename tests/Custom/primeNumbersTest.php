<?php
declare(strict_types=1);

require_once realpath(__DIR__ . '/../../vendor/autoload.php');

use App\Services\MathOperations\PrimeNumberGenerator;

$testTimeLimit = 40;
$maxN = 1000000;

echo "Test will end when time for iteration exceeds $testTimeLimit seconds" . PHP_EOL . PHP_EOL;

// Parameters
$n = 1000; // Adjust N based on your needs for the performance test
$generator = new PrimeNumberGenerator();

while ($n <= $maxN) {
    echo "Testing n = " . number_format($n, 0, '.', ' ') . PHP_EOL;
    $executionTime = theTest($generator, $n);
    echo PHP_EOL;
    if ($executionTime < $testTimeLimit) {
        if ($n < 100000) {
            $n *= 10;
        } else {
            $n += 100000;
        }
    } else {
        exit;
    }
}

/**
 * @param $generator
 * @param $n
 *
 * @return float
 */
function theTest($generator, $n): float
{
    // Start timing
    $timeStart = microtime(true);

// Generate primes
    $primes = $generator->generatePrimes($n);

// End timing
    $timeEnd = microtime(true);
    $executionTime = $timeEnd - $timeStart;

// Memory usage
    $memoryUsage = memory_get_peak_usage(true) / 1024 / 1024; // Convert to MB

// Output results
    echo "Generated " . number_format(count($primes), 0, '.', ' ') . " prime numbers." . PHP_EOL;
    echo "Execution Time: " . number_format($executionTime, 3) . " seconds" . PHP_EOL;
    echo "Memory Usage: " . number_format($memoryUsage, 3) . " MB" . PHP_EOL;
    return $executionTime;
}
