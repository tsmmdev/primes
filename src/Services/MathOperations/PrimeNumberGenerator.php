<?php
declare(strict_types=1);

namespace App\Services\MathOperations;

class PrimeNumberGenerator
{
    /**
     * Generate the first N prime numbers.
     * Trial Division Method - for none large ranges
     *
     * @param int $n Number of primes to generate
     *
     * @return array
     */
    public function generatePrimes(int $n): array
    {
        $primes = [];
        $number = 2;

        // 2 is the only even prime number
        if ($n > 0) {
            $primes[] = $number;
            $number++;
        }

        while (count($primes) < $n) {
            if ($this->isPrime($number)) {
                $primes[] = $number;
            }
            $number += 2; // Skip even numbers (except for 2)
        }

        return $primes;
    }

    /**
     * Check if a number is prime.
     *
     * @param int $num The number to check
     *
     * @return bool True if the number is prime, false otherwise
     */
    public function isPrime(int $num): bool
    {
        if ($num < 2) {
            return false;
        }

        for ($i = 2; $i <= sqrt($num); $i++) {
            if ($num % $i === 0) {
                return false;
            }
        }

        return true;
    }
}
