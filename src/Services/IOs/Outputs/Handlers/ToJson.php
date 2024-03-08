<?php
declare(strict_types=1);

namespace App\Services\IOs\Outputs\Handlers;

use App\Services\IOs\Outputs\Interfaces\OutputInterface;

class ToJson implements OutputInterface
{
    /**
     * Process to output
     *
     * @param string $destination
     * @param array $primes
     * @param array $table
     *
     * @return bool
     */
    public function doOutput(
        string $destination,
        array $primes,
        array $table
    ): bool {
        if (!empty($table)) {
            $result[0][] = "*";
            $result[0] = array_merge($result[0], $primes);
            foreach ($table as $keyY => $arrayX) {
                array_unshift($arrayX, $primes[$keyY]);
                $result[$keyY + 1] = $arrayX;
            }
            $result = json_encode($result);
            file_put_contents($destination, $result);
        }
        return true;
    }
}
