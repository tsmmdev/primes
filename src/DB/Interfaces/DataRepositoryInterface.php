<?php
declare(strict_types=1);

namespace App\DB\Interfaces;

interface DataRepositoryInterface
{
    /**
     * Simple Data Repository Interface for simple saving and retrieving of data
     */

    /**
     * Save data to storage.
     *
     * @param array $primes An array of prime numbers
     * @param string $operation The operation associated with the data
     * @param array $data Table of numbers to be saved
     *
     * @return bool
     */
    public function saveData(
        array $primes,
        string $operation,
        array $data
    ): bool;

    /**
     * Retrieve data from storage.
     *
     * @param int $length Number of generated primes
     * @param string $operation The operation associated with the data
     *
     * @return array|null The retrieved data, or null if the data is not found
     */
    public function getData(
        int $length,
        string $operation
    ): ?array;
}
