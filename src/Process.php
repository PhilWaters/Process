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
     * Stores current command
     *
     * @var string
     */
    private $cmd = null;

    /**
     * Stores commands to run
     *
     * @var string
     */
    private $cmds;

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
     * Stores stdin
     *
     * @var string
     */
    private $stdin = null;

    /**
     * Stores proc_open descriptor spec
     *
     * @var string
     */
    private $descriptorSpec = array(
        array("pipe", "r"),
        array("pipe", "w"),
        array("pipe", "w")
    );

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
        $this->cmds[] = $this->cmd;

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
        $this->isCmdSet();
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
        $this->isCmdSet();
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
        $this->isCmdSet();
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
     * @param string $path Log file path
     * @param number $mode Log write mode (FILE_APPEND)
     *
     * @return Process
     */
    public function log($path, $mode = FILE_APPEND)
    {
        $this->stdout($path, $mode);
        $this->stderr($path, $mode);

        return $this;
    }

    /**
     * Sets STDIN source (file or pipe)
     *
     * @param mixed  $stdin File path or value to pass to STDIN
     * @param string $type  file or pipe
     *
     * @return Process
     */
    public function stdin($stdin, $type = "pipe")
    {
        if ($type == "pipe") {
            $this->descriptorSpec[0] = array($type, "r");
            $this->stdin = $stdin;
        } else {
            $this->descriptorSpec[0] = array($type, $stdin, "r");
            $this->stdin = null;
        }

        return $this;
    }

    /**
     * Sets STDOUT path
     *
     * @param string $path File path
     * @param string $mode Write mode (FILE_APPEND)
     *
     * @return Process
     */
    public function stdout($path, $mode = FILE_APPEND)
    {
        $this->descriptorSpec[1] = array("file", $path, $mode == FILE_APPEND ? "a" : "w");

        return $this;
    }

    /**
     * Sets STDERR path
     *
     * @param string $path File path
     * @param string $mode Write mode (FILE_APPEND)
     *
     * @return Process
     */
    public function stderr($path, $mode = FILE_APPEND)
    {
        $this->descriptorSpec[2] = array("file", $path, $mode == FILE_APPEND ? "a" : "w");

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

        $pipes = array();

        $process = proc_open($cmd, $this->descriptorSpec, $pipes, $this->cwd);

        if ($this->stdin !== null) {
            fwrite($pipes[0], $this->stdin);
        }

        if ($this->descriptorSpec[0][0] == "pipe") {
            fclose($pipes[0]);
        }

        if ($this->async) {
            return new Waiter($process);
        }

        if ($this->descriptorSpec[1][0] == "pipe") {
            $output = explode("\n", stream_get_contents($pipes[1]));
            fclose($pipes[1]);
        }

        $return = proc_close($process);

        if ($return != 0) {
            throw new \Exception("$cmd failed - return code $return", $return);
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
        $this->isCmdSet();

        return implode(" | ", $this->cmds);
    }

    /**
     * Checks if a command has been set
     *
     * @throws \Exception
     *
     * @return void
     */
    private function isCmdSet()
    {
        if ($this->cmd === null) {
            throw new \Exception("You must call cmd() first");
        }
    }
}
