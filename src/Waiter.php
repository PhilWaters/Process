<?php
/**
 * Waiter class
 *
 * @author Phil Waters <phil@waters.codes>
 */

namespace PhilWaters\Process;

/**
 * Waits for a process to complete
 *
 * @author Phil Waters <phil@waters.codes>
 */
class Waiter
{
    /**
     * Waiter constructor
     *
     * @param number $pid Process ID
     */
    public function __construct($process)
    {
        $this->process = $process;
    }

    /**
     * Wait for the process to finish
     *
     * @param number $timeout Number of seconds to wait. Pass null to wait indefinitely or until PHP process exits
     *                        based on max_execution_time
     *
     * @return boolean True, if process has completed, else, false
     */
    public function wait($timeout = null)
    {
        $count = 0;

        while ($timeout === null || $count++ < $timeout) {
            if (!$this->isRunning()) {
                return true;
            }

            sleep(1);
        }

        return false;
    }

    /**
     * Terminates process
     *
     * @param integer $signal Kill signal
     *
     * @return void
     */
    public function terminate($signal)
    {
        $status = proc_get_status($this->process);

        if (!proc_terminate($this->process, $signal)) {
            return false;
        }

        usleep(1000);

        $status = proc_get_status($this->process);

        return $status['signaled'] && !$status['running'];
    }

    /**
     * Checks if the process is still running
     *
     * @return boolean True, if process is running, else, false
     */
    private function isRunning()
    {
        $status = proc_get_status($this->process);

        return $status['running'];
    }
}
