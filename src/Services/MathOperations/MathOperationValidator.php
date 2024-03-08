<?php
declare(strict_types=1);

namespace App\Services\MathOperations;

use App\Exceptions\MathOperationException;
use App\Services\MathOperations\Interfaces\MathOperationInterface;

class MathOperationValidator
{
    private array $mathOperationHandlers;

    /**
     * @param array $mathOperationHandlers
     */
    public function __construct(
        array $mathOperationHandlers
    ) {
        $this->mathOperationHandlers = $mathOperationHandlers;
    }

    /**
     * @param $mathOperation
     *
     * @return MathOperationInterface
     *
     * @throws MathOperationException
     */
    public function validate(
        $mathOperation
    ): MathOperationInterface {

        if (isset($this->mathOperationHandlers[$mathOperation])) {
            $handler = $this->mathOperationHandlers[$mathOperation];
        } else {
            throw new MathOperationException("Invalid or unsupported math operation '$mathOperation'!");
        }

        if (!$handler instanceof MathOperationInterface) {
            throw new MathOperationException("Handler for '$mathOperation' does not implement MathOperationInterface!");
        }
        return $handler;
    }
}
