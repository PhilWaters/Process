<?php
/**
 * Process class
 *
 * @author Phil Waters <phil@waters.codes>
 */

namespace PhilWaters\Process;

require_once "Waiter.php";
require_once "Command.php";
require_once "Argument.php";
require_once "Option.php";

/**
 * Allows commands to be run
 *
 * @author Phil Waters <phil@waters.codes>
 */
class Process
{
    /**
     * Stores workding directory
     *
     * @var string
     */
    private $cwd = null;

    /**
     * Stores command to run
     *
     * @var string
     */
    private $cmd;

    /**
     * Stores command arguments
     *
     * @var array
     */
    private $args = array();

    /**
     * Stores whether or not to run command asynchronously
     *
     * @var boolean
     */
    private $async = false;

    /**
     * Stores log file path
     *
     * @var string
     */
    private $log = null;

    /**
     * Stores log file write mode
     *
     * @var number
     */
    private $mode = 0;

    /**
     * Sets working directory
     *
     * @param string $cwd Working directory
     *
     * @return void
     */
    public function cwd($cwd)
    {
        $this->cwd = $cwd;

        return $this;
    }

    /**
     * Sets the command to run
     *
     * @param string $cmd Command to run
     *
     * @return Process
     */
    public function cmd($cmd)
    {
        $this->cmd = new Command($cmd);

        return $this;
    }

    /**
     * Adds a command option
     *
     * @param string $option    Option
     * @param string $value     Value
     * @param string $separator Separator
     *
     * @throws \InvalidArgumentException
     *
     * @return Process
     */
    public function option($option, $value = null, $separator = " ")
    {
        $this->cmd->option($option, $value, $separator);

        return $this;
    }

    /**
     * Adds an argument
     *
     * @param string $arg Argument
     *
     * @return Process
     */
    public function arg($arg)
    {
        $this->cmd->arg($arg);

        return $this;
    }

    /**
     * Adds arguments
     *
     * @param string $arg,... Arguments
     *
     * @return Process
     */
    public function args($arg)
    {
        $args = func_get_args();

        foreach ($args as $arg) {
            $this->cmd->arg($arg);
        }

        return $this;
    }

    /**
     * Configures the process to run asynchronously. The run method will return a waiter instead of the output.
     *
     * @return Process
     */
    public function async()
    {
        $this->async = true;

        return $this;
    }

    /**
     * Sets the path to a log file
     *
     * @param string $log  Log file path
     * @param number $mode Log write mode (FILE_APPEND)
     *
     * @return Process
     */
    public function log($log, $mode = FILE_APPEND)
    {
        $this->log = $log;
        $this->mode = $mode;

        return $this;
    }

    /**
     * Runs the command
     *
     * @throws Exception
     *
     * @return Waiter|array If asynchronous is enabled, a Waiter instance, else, array of command output
     */
    public function run()
    {
        $cmd = $this->buildCommand();

        if ($this->async) {
            $pid = shell_exec($cmd);

            return new Waiter($pid);
        }

        exec($cmd, $output, $return);

        if ($return != 0) {
            throw new \Exception("$cmd failed - return code $return");
        }

        return $output;
    }

    /**
     * Builds command string
     *
     * @return string
     */
    public function buildCommand()
    {
        $cmd = $this->cmd;

        if (!empty($this->cwd)) {
            $cmd = "cd " . $this->cwd . " && " . $cmd;
        }

        if (!empty($this->log)) {
            $cmd .= " " . ($this->mode == FILE_APPEND ? ">>" : ">") . " " . $this->log;
        } elseif ($this->async) {
            $cmd .= " >/dev/null";
        }

        $cmd .= " 2>&1";

        if ($this->async) {
            $cmd .= " & echo $!";
        }

        return $cmd;
    }
}
