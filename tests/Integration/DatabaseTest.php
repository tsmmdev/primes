<?php
declare(strict_types=1);

namespace Integration;

use App\DB\Sources\MySQLConnection;
use PDO;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    /**
     * @return void
     */
    public function testConnection(): void
    {
        $db = new MySQLConnection(
            'localhost',
            'primes',
            'primes',
            'primes_password'
        );

        $this->assertInstanceOf(PDO::class, $db->getPDO(), 'Database connection should return a PDO instance.');
    }
}
