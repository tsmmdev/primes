<?php
declare(strict_types=1);

namespace Integration;

use App\Exceptions\ConfigurationException;
use App\Exceptions\JsonDecodingException;
use App\Services\Config\Config;
use App\Services\IOs\Inputs\Interfaces\InputInterface;
use App\Services\IOs\Outputs\Interfaces\OutputInterface;
use App\Services\MathOperations\Interfaces\MathOperationInterface;
use Closure;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    protected static Config $config;

    /**
     * @return void
     *
     * @throws ConfigurationException
     * @throws JsonDecodingException
     */
    protected function setUp(): void
    {
        parent::setUp();
        self::$config = new Config(realpath(__DIR__ . '/../../config.json'));
    }


    /**
     * @return void
     *
     * @throws ConfigurationException
     */
    public function testConfigEagerInstances()
    {
        $mathOperations = self::$config->createEagerInstances("math_handlers");

        $this->assertIsArray($mathOperations);
        $this->assertNotEmpty($mathOperations);
        $this->assertCount(4, $mathOperations);

        // Assertions for MathOperationInterface
        foreach ($mathOperations as $mathHandler) {
            $this->assertInstanceOf(MathOperationInterface::class, $mathHandler);
        }
    }

    /**
     * @return void
     *
     * @throws ConfigurationException
     */
    public function testConfigEagerInstancesFails(): void
    {
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('Invalid config.json structure for handlers');
        self::$config->createEagerInstances("none_existing_handlers");
    }

    /**
     * @return void
     *
     * @throws ConfigurationException
     */
    public function testConfigLazyInstancesOfInput(): void
    {
        $inputHandlers = self::$config->createLazyInstances("input_handlers");
        $this->assertIsArray($inputHandlers);
        $this->assertNotEmpty($inputHandlers);

        foreach ($inputHandlers as $handler) {
            $this->assertInstanceOf(Closure::class, $handler);
            $inputHandler = $handler();
            $this->assertInstanceOf(InputInterface::class, $inputHandler);
        }
    }

    /**
     * @return void
     *
     * @throws ConfigurationException
     */
    public function testConfigLazyInstancesOfOutputs(): void
    {
        $outputHandlers = self::$config->createLazyInstances("output_handlers");
        $this->assertIsArray($outputHandlers);
        $this->assertNotEmpty($outputHandlers);

        foreach ($outputHandlers as $handler) {
            $this->assertInstanceOf(Closure::class, $handler);
            $outputHandler = $handler();
            $this->assertInstanceOf(OutputInterface::class, $outputHandler);
        }
    }

    /**
     * @return void
     */
    public function testGetReturnsCorrectValue(): void
    {
        $expectedHost = 'localhost';
        $host = self::$config->get('database', 'host');
        $this->assertEquals($expectedHost, $host, "The host should be '$expectedHost'.");

        $dbConfig = self::$config->get('database');
        $this->assertIsArray($dbConfig);
        $this->assertCount(5, $dbConfig);
    }

    /**
     * @return void
     */
    public function testGetReturnsNullForNonExistingKey(): void
    {
        $result = self::$config->get('non_existing_key');
        $this->assertNull($result, "The result should be null for a non-existing key.");
    }
}
