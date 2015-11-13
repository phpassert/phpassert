<?php
namespace test\PHPAssert\Console\Utils\App;

use org\bovigo\vfs\vfsStream;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use function PHPAssert\Console\Utils\App\getApp;

class TestApp
{
    /**
     * @var Application
     */
    private $app;

    function beforeMethod()
    {
        $this->app = getApp();
    }

    function testGetAppShouldReturnApp()
    {
        assert($this->app instanceof Application);
    }

    function testAppShouldHaveTestCommand()
    {
        $command = $this->app->get('test');
        assert($command, new \AssertionError('Test command not found'));
    }
}
