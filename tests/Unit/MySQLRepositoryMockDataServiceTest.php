<?php
declare(strict_types=1);

namespace Unit;

use App\DB\Interfaces\DataRepositoryInterface;
use App\DB\MySQLRepository;
use PHPUnit\Framework\TestCase;

class DataProcessorService
{
    protected DataRepositoryInterface $repository;

    /**
     * @param DataRepositoryInterface $repository
     */
    public function __construct(DataRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $length
     * @param string $operation
     *
     * @return array
     */
    public function fetchData(int $length, string $operation): array
    {
        return $this->repository->getData($length, $operation);
    }
}

class MySQLRepositoryMockDataServiceTest extends TestCase
{
    public function testFetchDataRetrievesDataSuccessfully(): void
    {
        // Mock the MySQLRepository interface
        $repositoryMock = $this->createMock(MySQLRepository::class);

        // Prepare dummy data to return
        $dummyData = [
            'primes' => [2, 3, 5, 7],
            'table' => [
                [4, 6, 10, 14],
                [6, 9, 15, 21],
                [10, 15, 25, 35],
                [14, 21, 35, 49]
            ]
        ];

        $repositoryMock->expects($this->once())
            ->method('getData')
            ->with(
                $this->equalTo(4), // Expecting the length argument to be 4
                $this->equalTo('*') // Expecting the operation argument to be '*'
            )
            ->willReturn($dummyData);

        $dataProcessor = new DataProcessorService($repositoryMock);
        $result = $dataProcessor->fetchData(4, '*');

        $this->assertEquals($dummyData, $result);
    }
}
