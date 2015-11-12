<?php
namespace PHPAssert\Console\Command;


use PHPAssert\Core\Discoverer\FSDiscoverer;
use PHPAssert\Core\Reporter\ConsoleReporter;
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

        $discoverer = new FSDiscoverer($path);
        $reporter = new ConsoleReporter($output);
        $runner = new Runner($discoverer, $reporter);
        $runner->run();
    }
}
