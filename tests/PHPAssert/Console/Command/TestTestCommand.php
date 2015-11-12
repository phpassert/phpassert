<?php
namespace PHPAssert\Command;

use org\bovigo\vfs\vfsStream;
use PHPAssert\Console\Command\TestCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

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

    function testExecuteOnEmptyDirectory()
    {
        $this->assertExecution([], 'No tests were executed');
    }

    function testExecuteTest()
    {
        $structure = [
            'testSomething.php' => '<?php namespace PHPAssert\examples; function testExecution() {}'
        ];
        $expected = 'OK (1 tests)';
        $this->assertExecution($structure, $expected);
    }

    function assertExecution(array $fileStructure, \string $expected)
    {
        $app = new Application();
        $app->add($this->command);

        $fs = vfsStream::create($fileStructure, vfsStream::setup());

        $command = $app->find($this->command->getName());
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            'command' => $command->getName(),
            'path' => $fs->url()
        ]);

        $display = $commandTester->getDisplay();
        assert(strpos($display, $expected),
            new \AssertionError('Tests are not being executed'));
    }
}
