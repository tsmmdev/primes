## TASK

**Prime Number Multiplication Table**

**Objective:**
Write a PHP program called **_PrimeMultiplicationTable_** that generates a multiplication table of prime numbers. The program should be implemented with an emphasis on maintainability, scalability, and test-driven development (TDD).

**Requirements:**

1. **Command Line Program:**
    - The program should be executed from the command line.
    - It should print the multiplication table to STDOUT.

2. **Prime Number Generation:**
    - Implement your own prime number generator without using the builtin one.
    - The program should be capable of generating the first N prime numbers where N is a configurable parameter.

3. **Table Format:**
    - The first row and first column of the table should display the generated prime numbers.
    - Each cell in the table should contain the product of the prime numbers for the corresponding row and column.

4. **Maintainability and SRP:**
    - Follow the Single Responsibility Principle (SRP) when designing your classes and methods.
    - Write clean, well-structured, and maintainable code.
    - Use meaningful variable and function names.

5. **Performance and Scalability:**
    - Optimize the prime number generation algorithm for performance.
    - Assess and document the time complexity of your code.
    - Ensure that the program runs efficiently for large values of N.

6. **Unit Tests (TDD):**
    - Develop the program using a Test-Driven Development (TDD) approach.
    - Write unit tests to ensure that your code functions correctly.
    - Use a testing framework or create your own test suite.

7. **Custom Operation Functions:**
    - Allow the user to define custom operation functions through the command line. This will allow your program to support more that jus multiplication.

8. **Database Storage:**
    - Implement a storage mechanism to save the generated tables to a MySQL database.

9. **Extendable Input and Output**
    - Make the input and output mechanisms extendable. Users should be able to add new input and output methods.


**Deliverables:**

1. A well-organized PHP program that meets the requirements outlined above.
2. A README file that provides clear instructions on how to run the program, including any required command-line parameters.
3. A separate section in the README that explains the prime number generation algorithm used and the reasoning behind it.
4. A section in the README that discusses the time complexity of the code and its efficiency for different values of N.
5. A test suite that thoroughly tests the functionality of the program.


**Note:**
Please ensure that your code is well-documented, and your README is informative and easy to follow. A clear demonstration of your understanding of maintainable code, efficient algorithms and testing will be

--------------------

**STRUCTURE**
```text
.
├── src
│   ├── DB
│   │   ├── Interfaces
│   │   │   └── DataRepositoryInterface.php
│   │   ├── Sources
│   │   │   └── MySQLConnection.php
│   │   └── MySQLRepository.php
│   ├── Exceptions
│   │   ├── ArgumentValidationException.php
│   │   ├── ConfigurationException.php
│   │   ├── InputTypeException.php
│   │   ├── JsonDecodingException.php
│   │   ├── MathOperationException.php
│   │   ├── MySqlException.php
│   │   ├── OutputTypeException.php
│   │   └── RepositoryException.php
│   ├── Helpers
│   │   ├── FileHelper.php
│   │   └── MessageHelper.php
│   └── Services
│       ├── Config
│       │   └── Config.php
│       ├── IOs
│       │   ├── Arguments
│       │   │   ├── ArgumentsParser.php
│       │   │   ├── ArgumentsValidator.php
│       │   │   └── HelpMessage.php
│       │   ├── Inputs
│       │   │   ├── Handlers
│       │   │   │   ├── FromJson.php
│       │   │   │   └── FromTxt.php
│       │   │   ├── Interfaces
│       │   │   │   └── InputInterface.php
│       │   │   └── InputValidator.php
│       │   └── Outputs
│       │       ├── Handlers
│       │       │   ├── ToJson.php
│       │       │   ├── ToStdout.php
│       │       │   └── ToTxt.php
│       │       ├── Interfaces
│       │       │   └── OutputInterface.php
│       │       └── OutputValidator.php
│       └── MathOperations
│           ├── Interfaces
│           │   └── MathOperationInterface.php
│           ├── Operations
│           │   ├── Divide.php
│           │   ├── Multiply.php
│           │   ├── Subtract.php
│           │   └── Sum.php
│           ├── MathOperationValidator.php
│           ├── PrimeNumberGenerator.php
│           └── TableGenerator.php
├── app.php
├── composer.json
├── composer.lock
├── config.json
├── input.json
├── input.txt
├── phpcs.xml
├── phpunit.xml
└── README.md
```

## **SETUP**
```composer install```
<br>
Provide credentials for MySQL in **config.json**. Firs run of the app should create the DB table.
<br>Some example **input files** are provided for argument testing as different inputs: _input.txt, input.json_

## **TESTS**

**All tests except custom**<br>
```php vendor/phpunit/phpunit/phpunit```

**Tests by testsuite**<br>
```php vendor/phpunit/phpunit/phpunit --testsuite integration```<br>

```php vendor/phpunit/phpunit/phpunit --testsuite unit```<br>

```php vendor/phpunit/phpunit/phpunit --testsuite end2end```<br>

### **Custom performance test for prime number generator**
php tests/Custom/primeNumbersTest.php

The provided script performs a performance test on the PrimeNumberGenerator class by incrementally generating prime numbers and measuring the time taken for each iteration. The test starts by generating 1,000 prime numbers and increases the number of primes generated in each subsequent iteration. If the number of primes to generate is below 100,000, it increases tenfold; above that, it increases by 100,000. The test continues until an iteration exceeds a time limit of 40 seconds, at which point the script terminates. This approach helps identify the performance threshold of the prime number generation process under increasing load.


## **EXECUTION**

```php app.php [arguments] ```

Usage options:
```text
-help: Prints this message
-h: Same as -help
-i: Input (If file should be placed in the same folder where the app IS EXECUTED). Supported formats are json and txt files. Optional.
-o: Output path. Supported formats are json and txt files. Optional. Default is `stdout` (monitor)
-n: Positive number of primes bigger than 2 no more than 3800. Always REQUIRED
-operation: Math operation. Optional. Default is '*' multiply. Also try / - +.
```

**Example:**
```php app.php -n=10 -operation=* -o=result.txt```

<br>

**PRIME NUMBER GENERATION**

The algorithm used is 'Trial Division,' which is straightforward for non-large ranges. Currently, this algorithm has been tested on a range of 500,000 numbers and works well. There are also algorithms for larger ranges: 'Sieve of Eratosthenes' and 'Sieve of Atkin,' but for this task, we will stay away from them.

<br>

**TIME COMPLEXITY OF THE CODE**

There are several limitations to avoid bottlenecks. Prime number generation handles well, and math operations also handle well. However, when it comes to storing the data to the database and file, things become challenging. One of the slowest tasks is pushing the result of operations to an array. Additionally, storing a serialized array or comma-separated string of the generated table is large and difficult to store in the database. A low number of sequences (around 2000) when saved to .txt files generates files around 300MB, and 15,000 numbers result in files around 3GB. For the current task, it is faster to generate the data every time without storing and retrieving it. Added compression for data to be stored to DB which gave 50% more capacity.

