<?php
/**
 * Escape class
 *
 * @author Phil Waters <phil@waters.codes>
 */

namespace PhilWaters\Process;

/**
 * Escapes commands and arguments
 *
 * @author Phil Waters <phil@waters.codes>
 */
class Escaper
{
    /**
     * Escape shell command
     *
     * @param string $command Command to escape
     *
     * @return string
     */
    public function command($command)
    {
        return escapeshellcmd($command);
    }

    /**
     * Escapes shell argument
     *
     * @param string $arg Argument to escape
     *
     * @return string
     */
    public function argument($argument)
    {
        return str_replace("*", "'*'", escapeshellarg($argument));
    }
}