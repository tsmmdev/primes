<?php
declare(strict_types=1);

namespace Integration;

use App\DB\MySQLRepository;
use App\DB\Sources\MySQLConnection;
use PHPUnit\Framework\TestCase;

class MySQLRepositoryTest extends TestCase
{
    private MySQLRepository $repository;
    private MySQLConnection $connection;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->connection = new MySQLConnection(
            'localhost',
            'primes',
            'primes',
            'primes_password'
        );
        $this->repository = new MySQLRepository($this->connection);

        // MySQLConnection should create database and table if it does not exist
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->connection->getPDO()->exec("TRUNCATE primes;");
    }

    /**
     * @return void
     */
    public function testDataSaveAndRetrieve(): void
    {
        $primes = [2, 3, 5, 7];
        $operation = '*';
        $data = [[4, 6, 10, 14], [6, 9, 15, 21], [10, 15, 25, 35], [14, 21, 35, 49]];

        // Test saving data
        $saved = $this->repository->saveData($primes, $operation, $data);
        $this->assertTrue($saved, "Data should be saved successfully.");

        // Test retrieving the same data
        $retrievedData = $this->repository->getData(count($primes), $operation);
        $this->assertNotNull($retrievedData, "Data should be retrievable.");
        $this->assertEquals($data, $retrievedData['table'], "Retrieved data should match the saved data.");
    }
}
