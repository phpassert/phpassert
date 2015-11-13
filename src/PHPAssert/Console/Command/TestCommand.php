<?php
namespace PHPAssert\Console\Command;


use PHPAssert\Console\Errors\FailedTestsException;
use PHPAssert\Core\Discoverer\FSDiscoverer;
use PHPAssert\Core\Reporter\ConsoleReporter;
use PHPAssert\Core\Result\Result;
use PHPAssert\Core\Runner\Runner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class TestCommand extends Command
{
    protected function configure()
    {
        $this->setName('test')
            ->setDescription('run all the tests in the specified directory')
            ->addArgument('path', InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fs = new Filesystem();
        $path = $input->getArgument('path') ?? 'tests';
        $path = $fs->isAbsolutePath($path) ? $path : getcwd() . DIRECTORY_SEPARATOR . $path;

        $runner = new Runner(new FSDiscoverer($path), new ConsoleReporter($output));
        $results = $runner->run();

        $failed = array_filter($results, function (Result $result) {
            return !$result->isSuccess();
        });

        if (count($failed) > 0) {
            throw new FailedTestsException();
        }
    }
}
