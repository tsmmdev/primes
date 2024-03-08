<?php
declare(strict_types=1);

namespace App\Services\IOs\Inputs\Handlers;

use App\Exceptions\InputTypeException;
use App\Services\IOs\Inputs\Interfaces\InputInterface;
use Exception;

// Justo for proof of concept we use the same logic as InputTxt
class FromJson implements InputInterface
{

    /**
     * Get arguments from source file
     *
     * @param string $source
     *
     * @return array
     *
     * @throws InputTypeException
     */
    public function getArguments(string $source): array
    {
        if (file_exists($source)) {
            try {
                // Format should be like: '{"n": 1000, "operation": "*", "o": "output.json"}'
                $data = file_get_contents($source);
                $data = json_decode($data, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception("Error decoding JSON: " . json_last_error_msg());
                }
                return $data;
            } catch (Exception $e) {
                throw new InputTypeException("Error opening file: '$source' : " . $e->getMessage());
            }
        } else {
            throw new InputTypeException("File does not exist: $source.");
        }
    }
}
