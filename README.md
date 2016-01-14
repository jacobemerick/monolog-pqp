# monolog-pqp

[![Build Status](https://travis-ci.org/jacobemerick/monolog-pqp.svg?branch=master)](https://travis-ci.org/jacobemerick/monolog-pqp)
[![Code Climate](https://codeclimate.com/github/jacobemerick/monolog-pqp/badges/gpa.svg)](https://codeclimate.com/github/jacobemerick/monolog-pqp)
[![Test Coverage](https://codeclimate.com/github/jacobemerick/monolog-pqp/badges/coverage.svg)](https://codeclimate.com/github/jacobemerick/monolog-pqp/coverage)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jacobemerick/monolog-pqp/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jacobemerick/monolog-pqp/?branch=master)

Monolog handler that interfaces with PHP Quick Profiler

## Installation
It's recommended that you use [Composer](https://getcomposer.org/) to install MonologPQP Handler.

```bash
$ composer require jacobemerick/monolog-pqp
```

This will install the handler and dependencies. It requires PHP 5.3.0 or newer.

## Usage
This is a handler for Monolog that will send logs and exceptions to PHP Quick Profiler. For more information about the profiler see [jacobemerick/pqp](https://github.com/jacobemerick/pqp).

```php
$console = new Particletree\Pqp\Console();
$profiler = new Particletree\Pqp\PhpQuickProfiler();
$profiler->setConsole();

$logger = new Monolog\Logger('web');
$handler = new Jacobemerick\MonologPqp\PqpHandler($console);
$logger->pushHandler($handler);

$logger->addDebug('PQP handler added to Monolog');
```

The default logging level for this handler is set to `Monolog\Logger::DEBUG`. For more information about this, or how to customize the format displayed in the profiler, see [Seldaek/monolog](https://github.com/Seldaek/monolog).

### Errors
PHP Quick Profiler handles exceptions separately, displaying more information about them and tagging as 'error'. If you simply do `$logger->error()` you will log the message but not get the extra sugar. The best way to handle this is by using Monolog as an exception handler.

```php
$logger = new Monolog\Logger('web');
$handler = new Jacobemerick\MonologPqp\PqpHandler($console);
$logger->pushHandler($handler);

Monolog\ErrorHandler::register($logger);
throw new Exception('testing');
```

This will trigger the `logError` method in the profiler and display additional data about the problem.
