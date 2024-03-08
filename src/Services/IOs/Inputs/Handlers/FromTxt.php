<?php
declare(strict_types=1);

namespace App\Services\IOs\Inputs\Handlers;

use App\Exceptions\InputTypeException;
use App\Services\IOs\Arguments\ArgumentsParser;
use App\Services\IOs\Inputs\Interfaces\InputInterface;
use Exception;

class FromTxt implements InputInterface
{
    private ArgumentsParser $argumentParser;

    public function __construct()
    {
        $this->argumentParser = new ArgumentsParser();
    }

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
                // Format should be like in cli: '-n=1000 -operation=* -o=output.json'
                $data = trim(file_get_contents($source));
                $data = $this->cleanDoubleSpaces($data);
                $arguments = explode(" ", $data);
                return $this->argumentParser->parse($arguments);
            } catch (Exception $e) {
                throw new InputTypeException("Error opening file: " . $e->getMessage());
            }
        } else {
            throw new InputTypeException("File does not exist: $source.");
        }
    }

    /**
     * @param string $string
     *
     * @return string
     */
    private function cleanDoubleSpaces(string $string): string
    {
        $string = str_replace("\n", ' ', $string);
        while (strpos($string, '  ') !== false) {
            $string = str_replace('  ', ' ', $string);
        }
        return $string;
    }
}
