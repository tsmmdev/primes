<?php
declare(strict_types=1);

namespace Unit;

use App\Services\MathOperations\PrimeNumberGenerator;
use PHPUnit\Framework\TestCase;

class TestablePrimeNumberGenerator extends PrimeNumberGenerator
{
    /**
     * @param int $num
     * @return bool
     */
    public function testIsPrime(int $num): bool
    {
        return $this->isPrime($num);
    }
}

class PrimeNumberGeneratorTest extends TestCase
{
    /**
     * @return void
     */
    public function testGeneratePrimes(): void
    {

        $primeGenerator = new PrimeNumberGenerator();

        $primes = $primeGenerator->generatePrimes(10);

        // The first 10 prime numbers
        $expectedPrimes = [2, 3, 5, 7, 11, 13, 17, 19, 23, 29];
        $this->assertEquals($expectedPrimes, $primes);
    }

    /**
     * @return void
     */
    public function testPrimeNumberGeneratorWithInvalidInput(): void
    {
        $primeNumberGenerator = new PrimeNumberGenerator();
        $this->assertEquals(
            [],
            $primeNumberGenerator->generatePrimes(0),
            'Should return an empty array for 0 primes'
        );
        $this->assertEquals(
            [],
            $primeNumberGenerator->generatePrimes(-5),
            'Should return an empty array for negative numbers of primes'
        );
    }

    /**
     * @return void
     */
    public function testIsPrime(): void
    {
        $testablePrimeGenerator = new TestablePrimeNumberGenerator();

        // Prime
        $this->assertTrue($testablePrimeGenerator->testIsPrime(2));
        $this->assertTrue($testablePrimeGenerator->testIsPrime(7));
        $this->assertTrue($testablePrimeGenerator->testIsPrime(13));

        // Non-prime
        $this->assertFalse($testablePrimeGenerator->testIsPrime(1));
        $this->assertFalse($testablePrimeGenerator->testIsPrime(4));
        $this->assertFalse($testablePrimeGenerator->testIsPrime(10));
    }
}
