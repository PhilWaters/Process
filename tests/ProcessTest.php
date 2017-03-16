<?php
require_once __DIR__ . "/../src/Process.php";

use \PhilWaters\Process\Process;

class ProcessTest extends PHPUnit_Framework_TestCase
{
    public function testOption()
    {
        $logPath = "/tmp/ls.log";
        $process = new Process();

        $waiter = $process
            ->cmd("ls")
            ->option("-l")
            ->args("/tmp")
            ->async()
            ->log($logPath)
            ->run();

        $this->assertTrue($waiter instanceof \PhilWaters\Process\Waiter);

        $waiter->wait(5);

        $this->assertTrue(file_exists($logPath));
    }

    public function testOption_withValue()
    {
        $process = new Process();

        $output = $process
            ->cmd("ls")
            ->option("-l", __DIR__)
            ->run();

        $this->assertContains("phpunit.xml", implode("\n", $output));
    }

    public function testCwd()
    {

        $process = new Process();

        $output = $process
            ->cwd(__DIR__ . "/../src")
            ->cmd("ls")
            ->option("-l", __DIR__)
            ->run();

        $this->assertContains("Process.php", implode("\n", $output));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testOption_invalid()
    {
        $process = new Process();

        $output = $process
            ->cmd("ls")
            ->option("| rm -rf /");
    }

    /**
     * @expectedException \Exception
     */
    public function testCommand_invalid()
    {
        $process = new Process();

        $process->cmd("hfjkdshkjghfdjkgdf")->run();
    }
}
