<?php

namespace Jacobemerick\MonologPqp;

use Particletree\Pqp\Console;
use PHPUnit_Framework_TestCase;

class PqpHandlerTest extends PHPUnit_Framework_TestCase
{

    public function testIsInstanceOfPqpHandler()
    {
        $pqpHandler = new PqpHandler(new Console());
echo 'hi';
        $this->assertInstanceOf('Jacobemerick\MonologPqp\PqpHandler', $pqpHandler);
    }
}
