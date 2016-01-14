<?php

namespace Jacobemerick\MonologPqp;

use Exception;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Particletree\Pqp\Console;
use PHPUnit_Framework_TestCase;
use ReflectionClass;

class PqpHandlerTest extends PHPUnit_Framework_TestCase
{

    public function testIsInstanceOfPqpHandler()
    {
        $pqpHandler = new PqpHandler(new Console());
        $this->assertInstanceOf('Jacobemerick\MonologPqp\PqpHandler', $pqpHandler);
    }

    public function testConstructSetsParent()
    {
        $pqpHandler = new PqpHandler(new Console(), Logger::NOTICE, false);

        $this->assertAttributeEquals(Logger::NOTICE, 'level', $pqpHandler);
        $this->assertAttributeEquals(false, 'bubble', $pqpHandler);
    }

    public function testConstructSetsDefaults()
    {
        $pqpHandler = new PqpHandler(new Console());

        $this->assertAttributeEquals(Logger::DEBUG, 'level', $pqpHandler);
        $this->assertAttributeEquals(true, 'bubble', $pqpHandler);
    }

    public function testConstructSetsConsole()
    {
        $console = new Console();
        $pqpHandler = new PqpHandler($console);

        $this->assertAttributeInstanceOf('Particletree\Pqp\Console', 'console', $pqpHandler);
        $this->assertAttributeSame($console, 'console', $pqpHandler);
    }

    /**
     * @dataProvider dataWrite
     */
    public function testWrite($message, $level)
    {
        $reflectedHandler = new ReflectionClass('Jacobemerick\MonologPqp\PqpHandler');
        $reflectedWriteMethod = $reflectedHandler->getMethod('write');
        $reflectedWriteMethod->setAccessible(true);

        $pqpHandler = new PqpHandler(new Console());
        $reflectedWriteMethod->invokeArgs($pqpHandler, array(
            array(
                'level' => $level,
                'formatted' => $message,
            )
        ));

        $expectedConsole = new Console();
        $expectedConsole->log($message);

        $this->assertAttributeEquals($expectedConsole, 'console', $pqpHandler);
    }

    public function dataWrite()
    {
        return array(
            array(
                'message' => 'site.DEBUG: Puppies',
                'level' => Logger::DEBUG,
            ),
            array(
                'message' => 'site.ERROR: Kittens',
                'level' => Logger::ERROR,
            ),
        );
    }

    /**
     * @dataProvider dataWriteExceptions
     */
    public function testWriteExceptions($exception, $level)
    {
        $reflectedHandler = new ReflectionClass('Jacobemerick\MonologPqp\PqpHandler');
        $reflectedWriteMethod = $reflectedHandler->getMethod('write');
        $reflectedWriteMethod->setAccessible(true);

        $pqpHandler = new PqpHandler(new Console());
        $reflectedWriteMethod->invokeArgs($pqpHandler, array(
            array(
                'level' => $level,
                'context' => array('exception' => $exception),
            )
        ));

        $expectedConsole = new Console();
        $expectedConsole->logError($exception);

        $this->assertAttributeEquals($expectedConsole, 'console', $pqpHandler);
    }

    public function dataWriteExceptions()
    {
        return array(
            array(
                'exception' => new Exception('Testing ERROR level'),
                'level' => Logger::ERROR,
            ),
            array(
                'exception' => new Exception('Testing CRITICAL level'),
                'level' => Logger::CRITICAL,
            ),
        );
    }

    public function testDefaultFormatterInstanceOfLineFormatter()
    {
        $reflectedHandler = new ReflectionClass('Jacobemerick\MonologPqp\PqpHandler');
        $reflectedFormatterMethod = $reflectedHandler->getMethod('getDefaultFormatter');
        $reflectedFormatterMethod->setAccessible(true);

        $pqpHandler = new PqpHandler(new Console());
        $formatter = $reflectedFormatterMethod->invoke($pqpHandler);

        $this->assertInstanceOf('Monolog\Formatter\LineFormatter', $formatter);
    }

    public function testDefaultFormatterSetsFormat()
    {
        $reflectedHandler = new ReflectionClass('Jacobemerick\MonologPqp\PqpHandler');
        $reflectedFormatterMethod = $reflectedHandler->getMethod('getDefaultFormatter');
        $reflectedFormatterMethod->setAccessible(true);

        $pqpHandler = new PqpHandler(new Console());
        $formatter = $reflectedFormatterMethod->invoke($pqpHandler);

        $this->assertAttributeEquals("%channel%.%level_name%: %message%\n", 'format', $formatter);
    }
}
