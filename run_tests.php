<?php
require_once('bootstrap.php');
use PHPAssert\Core\Discoverer\FSDiscoverer;
use PHPAssert\Core\Reporter\ConsoleReporter;
use PHPAssert\Core\Runner\Runner;
use Symfony\Component\Console\Output\ConsoleOutput;

$discoverer = new FSDiscoverer(__DIR__ . DIRECTORY_SEPARATOR . 'tests');
$reporter = new ConsoleReporter(new ConsoleOutput());
$runner = new Runner($discoverer, $reporter);
$runner->run();