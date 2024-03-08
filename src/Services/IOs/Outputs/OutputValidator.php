<?php
declare(strict_types=1);

namespace App\Services\IOs\Outputs;

use App\Exceptions\OutputTypeException;
use App\Helpers\FileHelper;
use App\Services\IOs\Outputs\Interfaces\OutputInterface;

class OutputValidator
{
    private array $outputHandlers;

    public function __construct(
        array $outputHandlers
    ) {
        $this->outputHandlers = $outputHandlers;
    }

    /**
     * @param $outputPath
     *
     * @return OutputInterface
     *
     * @throws OutputTypeException
     */
    public function validate(
        $outputPath
    ): OutputInterface {
        $outputFileType = FileHelper::getFileType($outputPath);

        // Instantiate the math operation handler lazily through closures
        if (isset($this->outputHandlers[$outputFileType])) {
            $handlerClass = $this->outputHandlers[$outputFileType];
            $handler = $handlerClass();
        } else {
            throw new OutputTypeException("Output type for '$outputPath' is not supported!");
        }

        if (!$handler instanceof OutputInterface) {
            throw new OutputTypeException("Handler for '$outputPath' does not implement OutputInterface!");
        }
        return $handler;
    }
}
