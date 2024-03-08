<?php
declare(strict_types=1);

namespace Integration;

use App\Exceptions\InputTypeException;
use App\Services\IOs\Inputs\Handlers\FromTxt;
use PHPUnit\Framework\TestCase;

class FromTxtTest extends TestCase
{

    /**
     * @return void
     *
     * @throws InputTypeException
     */
    public function testCanReadAndParseTxtInput(): void
    {
        $handler = new FromTxt();
        $filePath = __DIR__ . '/../InputSamples/input.txt';

        $expected = [
            'n' => '10',
            'operation' => '*',
            'o' => 'output.txt',
        ];

        $result = $handler->getArguments($filePath);
        $this->assertEquals($expected, $result, 'FromTxt should correctly parse TXT input.');
    }

    /**
     * @return void
     *
     * @throws InputTypeException
     */
    public function testTxtInputWithMissingFile(): void
    {
        $handler = new FromTxt();
        $missingFilePath = __DIR__ . '/../InputSamples/nonexistent.txt';

        $this->expectException(InputTypeException::class);
        $this->expectExceptionMessageMatches('/^File does not exist:/');
        $handler->getArguments($missingFilePath);
    }
}
