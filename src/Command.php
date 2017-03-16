<?php
/**
* Command class
*
* @author Phil Waters <phil@waters.codes>
*/

namespace PhilWaters\Process;

require_once "Escaper.php";
require_once "Argument.php";
require_once "Option.php";

/**
 * Stores process command
 *
 * @author Phil Waters <phil@waters.codes>
 */
class Command
{
    /**
     * Stores command
     *
     * @var string
     */
    private $cmd;

    /**
     * Stores arguments
     *
     * @var array
     */
    private $args = array();

    /**
     * Argument constructor
     *
     * @param string $cmd Command
     */
    public function __construct($cmd)
    {
        $escaper = new Escaper();

        $this->cmd = $escaper->command($cmd);
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
        $this->args[] = new Argument($arg);
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
        $this->args[] = new Option($option, $value, $separator);
    }

    /**
     * Gets command string
     *
     * @return string
     */
    public function __toString()
    {
        $cmd = escapeshellcmd($this->cmd);

        if (!empty($this->args)) {
            $cmd .= " " . implode(" " , $this->args);
        }

        return $cmd;
    }
}