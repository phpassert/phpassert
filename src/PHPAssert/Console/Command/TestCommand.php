<?php
namespace PHPAssert\Console\Command;


use PHPAssert\Core\Discoverer\FSDiscoverer;
use PHPAssert\Core\Reporter\ConsoleReporter;
use PHPAssert\Core\Result\Result;
use PHPAssert\Core\Runner\Runner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class TestCommand extends Command
{
    protected function configure()
    {
        $this->setName('test')
            ->setDescription('run all the tests in the specified directory')
            ->addArgument('path', InputArgument::OPTIONAL)
            ->addOption('bootstrap', 'b', InputOption::VALUE_OPTIONAL, 'File to include before running the tests');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bootstrap = $input->getOption('bootstrap');
        if ($bootstrap) {
            require_once($this->toAbsolutePath($bootstrap));
        }

        $path = $input->getArgument('path') ?? 'tests';
        $runner = new Runner(new FSDiscoverer($this->toAbsolutePath($path)), new ConsoleReporter($output));
        $results = $runner->run();

        $failed = array_filter($results, function (Result $result) {
            return !$result->isSuccess();
        });

        return intval(count($failed) > 0);
    }

    private function toAbsolutePath($path)
    {
        $fs = new Filesystem();
        return $fs->isAbsolutePath($path) ? $path : getcwd() . DIRECTORY_SEPARATOR . $path;

    }
}
