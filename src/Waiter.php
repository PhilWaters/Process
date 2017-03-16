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
    public function __construct($pid)
    {
        $this->pid = $pid;
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
            if (!$this->isRunning($this->pid)) {
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
        shell_exec(sprintf("kill -%d %d", $signal, $this->pid));

        return !$this->isRunning($this->pid);
    }

    /**
     * Checks if the process is still running
     *
     * @param number $pid Process ID
     *
     * @return boolean True, if process is running, else, false
     */
    private function isRunning($pid)
    {
        try {
            $result = shell_exec(sprintf("ps %d", $pid));

            if (count(preg_split("/\n/", $result)) > 2) {
                return true;
            }
        } catch (Exception $e) {
        }

        return false;
    }
}
