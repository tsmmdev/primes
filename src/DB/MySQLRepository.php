<?php
declare(strict_types=1);

namespace App\DB;

use App\DB\Interfaces\DataRepositoryInterface;
use App\DB\Sources\MySQLConnection;
use PDO;

class MySQLRepository implements DataRepositoryInterface
{
    /**
     * @var PDO
     */
    protected PDO $pdo;

    /**
     * @param MySQLConnection $dbConnection
     */
    public function __construct(
        MySQLConnection $dbConnection
    ) {
        $this->pdo = $dbConnection->getPDO();
    }

    /**
     * @param array $primes
     * @param string $operation
     * @param array $data
     *
     * @return bool
     */
    public function saveData(
        array $primes,
        string $operation,
        array $data
    ): bool {
        $length = count($primes);

        $prepare = $this->pdo->prepare("
        INSERT INTO primes (length, operation, primes, data)
        VALUES (:length, :operation, :primes, :data)
        ON DUPLICATE KEY UPDATE
        primes = VALUES(primes), data = VALUES(data)
    ");

//        $stringData = serialize($data);
        $stringData = json_encode($data);
        $compressedData = gzcompress($stringData);
//        var_dump(strlen($compressedData));

        $primesString = implode(',', $primes);

        $stmt = $prepare;

        $stmt->bindParam(':length', $length);
        $stmt->bindParam(':operation', $operation);
        $stmt->bindParam(':primes', $primesString);
        $stmt->bindParam(':data', $compressedData);

        $result = $stmt->execute();

        return $result !== false;
    }

    /**
     * @param int $length
     * @param string $operation
     * @return array|null
     */
    public function getData(int $length, string $operation): ?array
    {
        $stmt = $this->pdo->prepare("SELECT primes, data FROM primes WHERE length = :length AND operation = :operation");
        $stmt->bindParam(':length', $length);
        $stmt->bindParam(':operation', $operation);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            return null;
        }

        $result['primes'] = array_map('intval', explode(",", $result['primes']));
        $result['table'] = json_decode(gzuncompress($result['data']), true);

        return $result;
    }
}
