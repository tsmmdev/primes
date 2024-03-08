<?php
declare(strict_types=1);

namespace App\Services\IOs\Arguments;

class HelpMessage
{
    private array $options;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function generate(): string
    {
        $result = "Usage options:" . PHP_EOL;
        foreach ($this->options as $key => $description) {
            $result .= "-$key: $description" . PHP_EOL;
        }
        $result .= "Example: -n=10 -operation=* -o=result.txt" . PHP_EOL;
        return $result;
    }
}
