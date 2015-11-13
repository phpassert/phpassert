<?php
namespace PHPAssert\Console\Utils\App;

use PHPAssert\Console\Command\TestCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleExceptionEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;

function getApp(): Application
{
    $app = new Application();
    $app->add(new TestCommand());
    $app->setDefaultCommand('test');
    return $app;
}
