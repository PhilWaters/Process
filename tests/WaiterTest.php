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

    public function testTermincate_hangUp()
    {
        $process = new Process();

        $waiter = $process
            ->cmd("sleep")
            ->arg(10)
            ->async()
            ->run();

        $this->assertTrue($waiter->terminate(1));
    }

    public function testTermincate_nohup()
    {
        $process = new Process();

        $waiter = $process
            ->cmd("nohup sleep")
            ->arg(10)
            ->async()
            ->run();

        $this->assertFalse($waiter->terminate(1));
        $this->assertTrue($waiter->terminate(9));

        if (file_exists("nohup.out")) {
            unlink("nohup.out");
        }
    }
}
