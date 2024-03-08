<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\DB\MySQLRepository;
use App\DB\Sources\MySQLConnection;
use App\Exceptions\RepositoryException;
use App\Helpers\MessageHelper;
use App\Services\Config\Config;
use App\Services\IOs\Arguments\ArgumentsParser;
use App\Services\IOs\Arguments\ArgumentsValidator;
use App\Services\IOs\Arguments\HelpMessage;
use App\Services\IOs\Inputs\InputValidator;
use App\Services\IOs\Outputs\OutputValidator;
use App\Services\MathOperations\MathOperationValidator;
use App\Services\MathOperations\PrimeNumberGenerator;
use App\Services\MathOperations\TableGenerator;

try {

    // SETUP
    $config = new Config(__DIR__ . '/config.json');

    // There is a bottleneck saving bigger data, so we set limit. See comments bellow -> STORE DATA
    $maxPrimesToGenerate = $config->get('arguments', 'max_min_validations')['n']['max'];

    $mySQLConnection = new MySQLConnection(
        $config->get('database', 'host'),
        $config->get('database', 'database'),
        $config->get('database', 'username'),
        $config->get('database', 'password'),
        $config->get('database', 'port')
    );
    $dataRepository = new MySQLRepository($mySQLConnection);
    $argumentParser = new ArgumentsParser();
    $helpMessageService = new HelpMessage($config->get('arguments', 'valid_options'));
    $primeNumberGenerator = new PrimeNumberGenerator();
    $tableGenerator = new TableGenerator();
    $argumentsValidator = new ArgumentsValidator(
        $config->get('arguments', 'valid_options'),
        $config->get('arguments', 'defaults'),
        $config->get('arguments', 'max_min_validations'),
        $config->get('arguments', 'mandatory')
    );

    // VALIDATORS SETUP
    $inputValidator = new InputValidator(
        $config->createLazyInstances("input_handlers")
    );
    $outputValidator = new OutputValidator(
        $config->createLazyInstances("output_handlers")
    );
    $mathOperationValidator = new MathOperationValidator(
        $config->createEagerInstances("math_handlers")
    );


    // Initialize arguments and parse them
    array_shift($argv); // Remove the first argument (script name)
    $arguments = $argv;
    $inputArguments = $argumentParser->parse($arguments);

    // Display help if needed and ignore other arguments
    if (isset($inputArguments['help'])) {
        MessageHelper::finalMessage($helpMessageService->generate());
    }


    // VALIDATIONS
    if (isset($inputArguments['i'])) {
        // Input validation
        $inputHandler = $inputValidator->validate($inputArguments['i']);
        $inputArguments = $inputHandler->getArguments($inputArguments['i']);
    }

    // Argument validation
    $inputArguments = $argumentsValidator->validate($inputArguments);

    // Output validation
    $outputDestination = $inputArguments['o'];
    $outputHandler = $outputValidator->validate($outputDestination);

    // Math operation validation
    $mathOperationHandler = $mathOperationValidator->validate($inputArguments['operation']);
    $mathOperation = $inputArguments['operation'];


    // MATH OPERATIONS
    $numberOfPrimes = $inputArguments['n'];

    //check Repository for result
    $dbTable = $dataRepository->getData(
        $numberOfPrimes,
        $mathOperation
    );

    $dataFromDb = false;
    if ($dbTable === null) {
        // If no repository result do calculations
        $primeNumbers = $primeNumberGenerator->generatePrimes($numberOfPrimes);

        // Generate number table
        $numberTable = $tableGenerator->generateTable(
            $primeNumbers,
            $mathOperationHandler
        );
    } else {
        $dataFromDb = true;
        $primeNumbers = $dbTable['primes'];
        $numberTable = $dbTable['table'];
    }

    // NOTE: BOTTLENECK: it is faster to calculate the result instead to store it.
    // But to be compliant with the task ... we will just write result to DB only number of primes is less than a certain number


    // STORE DATA

    // NOTE: BOTTLENECK: trying to store at once large amount of data
    // 'PHP Warning:  Packets out of order. Expected 6 received 5. Packet size=60 in /home/user/Documents/Projects/primes/src/Services/DB/Repository/MySQLRepository.php on line 50'

    // Set limitation for storing data
    if (!$dataFromDb && count($primeNumbers) <= $maxPrimesToGenerate) {
        //skip saving if already we retrieved it from db
        $dataSaved = $dataRepository->saveData($primeNumbers, $inputArguments['operation'], $numberTable);
        if (!$dataSaved) {
            throw new RepositoryException("Data not stored to DB! Probably too large!");
        }
    }


    // OUTPUT
    $outputHandler->doOutput(
        $outputDestination,
        $primeNumbers,
        $numberTable
    );


} catch (Exception $e) {
    MessageHelper::finalMessage($e->getMessage());
}
