<?php

namespace Jacobemerick\MonologPqp;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Particletree\Pqp\Console;

class PqpHandler extends AbstractProcessingHandler
{

    /**
     * @var Console
     */
    protected $console;

    /**
     * @param Console $console
     * @param int     $level
     * @param boolean $bubble
     */
    public function __construct(Console $console, $level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->console = $console;
    }

    /**
     * @param array $record
     */
    protected function write(array $record)
    {
        // if error, log to error
        $this->console->log($record['formatted']);
    }

    /**
     * @return LineFormatter
     */
    protected function getDefaultFormatter()
    {
        return new LineFormatter();
    }
}
