<?php
ini_set('assert.exception', 1);
require_once('vendor/autoload.php');

use Symfony\Component\Console\Application;
use PHPAssert\Console\Command\TestCommand;

$app = new Application();
$app->add(new TestCommand());
$app->setDefaultCommand('test');
$app->run();
