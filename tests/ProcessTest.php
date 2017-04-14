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

    public function testMultipleCommands()
    {
        $process = new Process();

        $result = $process
            ->cmd("ls")
            ->cmd("grep")
            ->option("-v", "php")
            ->run();

        $this->assertContains("README.md", $result);
    }

    public function testOption_withValue()
    {
        $process = new Process();

        $output = $process
            ->cmd("ls")
            ->option("-l", __DIR__ . "/..")
            ->run();

        $this->assertContains("phpunit.xml", implode("\n", $output));
    }

    public function testCwd()
    {
        $process = new Process();

        $output = $process
            ->cwd(__DIR__ . "/../src")
            ->cmd("ls")
            ->option("-l")
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

    public function testStdin()
    {
        $process = new Process();

        $this->assertContains("toast", $process->stdin("test")->cmd("sed")->arg("s/e/oa/g")->run());

        $process = new Process();

        $response = $process->stdin(__DIR__ . "/data/input.txt", "file")->cmd("grep")->arg("alpha")->run();

        $this->assertContains("alpha", $response);
        $this->assertNotContains("beta", $response);
        $this->assertNotContains("charlie", $response);
    }

    /**
     * @expectedException \Exception
     */
    public function testInvalid()
    {
        $process = new Process();

        $process->arg("test");
    }

    public function testEnv()
    {
        $process = new Process();

        $this->assertContains(
            "This is a test",
            $process
                ->env(array("PHP_TEST" => "This is a test"))
                ->cmd("php")
                ->option("-r", "echo getenv('PHP_TEST');")
                ->run()
            );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testEnv_invalid()
    {
        $process = new Process();

        $process->env("INVALID");
    }
}
