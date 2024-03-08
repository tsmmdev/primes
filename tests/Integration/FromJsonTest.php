<?php
declare(strict_types=1);

namespace Integration;

use App\Exceptions\InputTypeException;
use PHPUnit\Framework\TestCase;
use App\Services\IOs\Inputs\Handlers\FromJson;

class FromJsonTest extends TestCase
{
    /**
     * @return void
     *
     * @throws InputTypeException
     */
    public function testCanReadAndParseJsonInput(): void
    {
        $handler = new FromJson();
        $filePath = __DIR__ . '/../InputSamples/input.json';

        $expected = [
            'n' => 10,
            'operation' => '*',
            'o' => 'output.json',
        ];

        $result = $handler->getArguments($filePath);
        $this->assertEquals($expected, $result, 'FromJson should correctly parse JSON input.');
    }

    /**
     * @return void
     *
     * @throws InputTypeException
     */
    public function testJsonInputWithMissingFileHasCorrectMessage(): void
    {
        $handler = new FromJson();
        $missingFilePath = __DIR__ . '/../InputSamples/nonexistent.json';

        $this->expectException(InputTypeException::class);
        $this->expectExceptionMessageMatches('/File does not exist:/');
        $handler->getArguments($missingFilePath);
    }

    /**
     * @return void
     *
     * @throws InputTypeException
     */
    public function testJsonInputWithMalformedDataHasCorrectMessage(): void
    {
        $handler = new FromJson();
        $malformedFilePath = __DIR__ . '/../InputSamples/malformed_input.json';

        $this->expectException(InputTypeException::class);
        $this->expectExceptionMessageMatches('/.*Error decoding JSON:.*/');
        $handler->getArguments($malformedFilePath);
    }
}
