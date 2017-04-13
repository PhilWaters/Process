<?php
require_once __DIR__ . "/../src/Process.php";
require_once __DIR__ . "/../src/Waiter.php";

use \PhilWaters\Process\Process;
use \PhilWaters\Process\Waiter;

class WaiterTest extends PHPUnit_Framework_TestCase
{
    public function testWait_ok()
    {
        $start = time();
        $process = new Process();

        $waiter = $process
            ->cmd("sleep")
            ->arg(2)
            ->async()
            ->run();

        $waiter->wait(5);
        $time = time() - $start;

        $this->assertTrue($time >= 2 && $time <= 3);
    }

    public function testWait_timeout()
    {
        $process = new Process();

        $waiter = $process
            ->cmd("sleep")
            ->arg(10)
            ->async()
            ->run();

        $this->assertFalse($waiter->wait(2));
    }

    public function testTerminate_hangUp()
    {
        $process = new Process();

        $waiter = $process
            ->cmd("sleep")
            ->arg(10)
            ->async()
            ->run();

        $this->assertTrue($waiter->terminate(SIGHUP));
    }

    public function testTerminate_nohup()
    {
        $process = new Process();

        $waiter = $process
            ->cmd("nohup sleep")
            ->arg("10")
            ->async()
            ->run();

        usleep(5000);
        $this->assertFalse($waiter->terminate(SIGHUP));
        usleep(5000);
        $this->assertTrue($waiter->terminate(SIGTERM));
        usleep(5000);
        $this->assertFalse($waiter->terminate(SIGHUP));

        if (file_exists("nohup.out")) {
            unlink("nohup.out");
        }
    }
}
