<?php

namespace Jacobemerick\MonologPqp;

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
