<?php
declare(strict_types=1);

namespace App\Services\IOs\Arguments;

use App\Exceptions\ArgumentValidationException;

class ArgumentsValidator
{
    private array $validArguments;
    private array $defaultArguments;
    private array $mandatoryArguments;
    private array $minMaxValidations;

    /**
     * @param array $validArguments
     * @param array $defaultArguments
     * @param array $minMaxValidations
     * @param array $mandatoryArguments
     */
    public function __construct(
        array $validArguments,
        array $defaultArguments = [],
        array $minMaxValidations = [],
        array $mandatoryArguments = []
    ) {
        $this->validArguments = $validArguments;
        $this->defaultArguments = $defaultArguments;
        $this->minMaxValidations = $minMaxValidations;
        $this->mandatoryArguments = $mandatoryArguments;
    }

    /**
     * @param array $inputArguments
     *
     * @return array
     *
     * @throws ArgumentValidationException
     */
    public function validate(
        array $inputArguments
    ): array {
        $returnArguments = [];

        foreach ($inputArguments as $key => $value) {
            if (!array_key_exists($key, $this->validArguments)) {
                throw new ArgumentValidationException("Invalid argument: '-$key'. See '-help' for instructions.");
            }

            // validate for ranges
            $value = $this->validateIntRange($key, $value);

            $returnArguments[$key] = $value;
        }

        // Validate that $returnOptions are not empty
        if (empty($returnArguments)) {
            throw new ArgumentValidationException("No arguments or invalid options are given.");
        }

        // Set defaults if none
        $returnArguments = $this->setDefaultArguments($returnArguments);

        $this->validateMandatoryArguments($returnArguments);

        return $returnArguments;
    }

    /**
     * Validate that mandatory arguments are present
     *
     * @param array $arguments
     *
     * @return void
     *
     * @throws ArgumentValidationException
     */
    private function validateMandatoryArguments(
        array $arguments
    ): void {
        $errorMessage = '';
        foreach ($this->mandatoryArguments as $mandatoryArgument) {
            if (!isset($arguments[$mandatoryArgument])) {
                $errorMessage .= "Mandatory argument '-$mandatoryArgument' not present. See '-help' for instructions." . PHP_EOL;
            }
        }
        if (!empty($errorMessage)) {
            throw new ArgumentValidationException($errorMessage);
        }
    }

    /**
     * Set default arguments if they are missing
     *
     * @param array $arguments
     *
     * @return array
     */
    private function setDefaultArguments(array $arguments): array
    {
        foreach ($this->defaultArguments as $option => $value) {
            if (!isset($arguments[$option])) {
                $arguments[$option] = $value;
            }
        }
        return $arguments;
    }


    /**
     * @param string $argument
     * @param $value
     *
     * @return mixed
     *
     * @throws ArgumentValidationException
     */
    private function validateIntRange(string $argument, $value)
    {
        if (isset($this->minMaxValidations[$argument])) {
            $minValidation = $this->minMaxValidations[$argument]['min'];
            $maxValidation = $this->minMaxValidations[$argument]['max'];
            $value = intval($value);
            if ($value < $minValidation || $value > $maxValidation) {
                throw new ArgumentValidationException("Argument '-$argument' must be between " . $minValidation . " and " . $maxValidation . ".");
            }
        }
        return $value;
    }
}
