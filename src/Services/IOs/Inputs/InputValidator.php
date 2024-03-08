<?php
declare(strict_types=1);

namespace App\Services\IOs\Inputs;

use App\Exceptions\InputTypeException;
use App\Helpers\FileHelper;
use App\Services\IOs\Inputs\Interfaces\InputInterface;

class InputValidator
{
    private array $inputHandlers;

    public function __construct(
        array $inputHandlers
    ) {
        $this->inputHandlers = $inputHandlers;
    }

    /**
     * @param $inputPath
     *
     * @return InputInterface
     *
     * @throws InputTypeException
     */
    public function validate(
        $inputPath
    ): InputInterface {
        if (file_exists($inputPath)) {
            $inputFileType = FileHelper::getFileType($inputPath);

            // Instantiate the math operation handler lazily through closures
            if (isset($this->inputHandlers[$inputFileType])) {
                $handlerClass = $this->inputHandlers[$inputFileType];
                $handler = $handlerClass();
            } else {
                throw new InputTypeException("Input type for '$inputPath' is not supported!");
            }

            if (!$handler instanceof InputInterface) {
                throw new InputTypeException("Handler for '$inputPath' does not implement InputInterface!");
            }
            return $handler;
        } else {
            throw new InputTypeException("File does not exist: '$inputPath'!");
        }
    }
}
