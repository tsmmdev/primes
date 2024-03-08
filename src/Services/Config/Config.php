<?php
declare(strict_types=1);

namespace App\Services\Config;

use App\Exceptions\ConfigurationException;
use App\Exceptions\JsonDecodingException;

class Config
{
    private array $configValues;

    /**
     * @param string $configPath
     *
     * @throws ConfigurationException
     * @throws JsonDecodingException
     */
    public function __construct(string $configPath)
    {
        $this->loadConfiguration($configPath);
    }

    /**
     * @param string $configPath
     *
     * @return void
     *
     * @throws ConfigurationException
     * @throws JsonDecodingException
     */
    private function loadConfiguration(string $configPath): void
    {
        if (!file_exists($configPath)) {
            throw new ConfigurationException("Configuration file not found: $configPath");
        }

        $configContent = file_get_contents($configPath);
        $this->configValues = json_decode($configContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonDecodingException("Error decoding JSON configuration file: " . json_last_error_msg());
        }
    }

    /**
     * @param string $key
     *
     * @param string|null $subKey
     *
     * @return mixed|null
     */
    public function get(string $key, string $subKey = null)
    {
        if (is_null($subKey)) {
            return $this->configValues[$key] ?? null;
        }
        return $this->configValues[$key][$subKey] ?? null;
    }

    /**
     * @param string $configKey
     *
     * @return array
     *
     * @throws ConfigurationException
     */
    public function createEagerInstances(string $configKey): array
    {
        $config = $this->get($configKey);
        if (!isset($config['base_path']) || !isset($config['classes'])) {
            throw new ConfigurationException("Invalid config.json structure for handlers");
        }

        $instances = [];
        foreach ($config['classes'] as $key => $className) {
            $fullClassName = $config['base_path'] . $className;
            if (class_exists($fullClassName)) {
                $instances[$key] = new $fullClassName();
            }
        }

        return $instances;
    }

    /**
     * @param string $configKey
     *
     * @return array
     *
     * @throws ConfigurationException
     */
    public function createLazyInstances(string $configKey): array
    {
        $config = $this->get($configKey);
        if (!isset($config['base_path']) || !isset($config['classes'])) {
            throw new ConfigurationException("Invalid config.json structure for handlers");
        }

        $instances = [];
        foreach ($config['classes'] as $key => $className) {
            $fullClassName = $config['base_path'] . $className;
            if (class_exists($fullClassName)) {
                $instances[$key] = function () use ($fullClassName) {
                    return new $fullClassName();
                };
            }
        }
        return $instances;
    }
}
