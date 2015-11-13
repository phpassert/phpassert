<?php
namespace test\PHPAssert\Console\Command;

use org\bovigo\vfs\vfsStream;
use PHPAssert\Console\Command\TestCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use function PHPAssert\Console\Utils\App\getApp;

class TestTestCommand
{
    /**
     * @var Command
     */
    private $command;

    function beforeMethod()
    {
        $this->command = new TestCommand();
    }

    function testInstanceOfCommand()
    {
        assert($this->command instanceof Command);
    }

    function testName()
    {
        assert('test' === $this->command->getName());
    }

    function testDescription()
    {
        assert('run all the tests in the specified directory' === $this->command->getDescription());
    }

    function testExitCodeOnSuccess()
    {
        $commandTester = $this->execute([
            'testSomething.php' => '<?php namespace PHPAssert\examples; function testExecution() {}'
        ]);
        assert($commandTester->getStatusCode() === 0);
    }

    function testExitCodeOnFailure()
    {
        $commandTester = $this->execute([
            'testFail.php' => '<?php namespace test\PHPAssert\Console; function testFail() {assert(false);}'
        ]);
        assert($commandTester->getStatusCode() === 1);
    }

    private function execute(array $fileStructure)
    {
        $app = getApp();
        $fs = vfsStream::create($fileStructure, vfsStream::setup());

        $command = $app->get($this->command->getName());
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'path' => $fs->url()
        ]);

        return $commandTester;
    }
}
