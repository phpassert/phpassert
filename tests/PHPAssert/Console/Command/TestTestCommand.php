<?php
namespace test\PHPAssert\Console\Command;

use org\bovigo\vfs\vfsStream;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use function PHPAssert\Console\Utils\App\getApp;

class TestTestCommand
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var Command
     */
    private $command;

    function beforeMethod()
    {
        $this->app = getApp();
        $this->command = $this->app->get('test');
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

    function testBootstrap()
    {
        try {
            $commandTester = $this->execute([], ['--bootstrap' => 'tests/fakebootstrap.php']);
        } catch (\Exception $e) {
            assert(false, new \AssertionError($e->getMessage()));
        }
    }

    private function execute(array $fileStructure, array $options = []): CommandTester
    {
        $fs = vfsStream::create($fileStructure, vfsStream::setup(getcwd()));
        $commandTester = new CommandTester($this->command);
        $commandTester->execute(array_merge([
            'command' => $this->command->getName(),
            'path' => $fs->url()
        ], $options));

        return $commandTester;
    }
}
