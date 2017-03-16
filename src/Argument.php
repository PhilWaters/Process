<?php
/**
* Command class
*
* @author Phil Waters <phil@waters.codes>
*/

namespace PhilWaters\Process;

require_once "Escaper.php";

/**
 * Stores process command
 *
 * @author Phil Waters <phil@waters.codes>
 */
class Argument
{
    private $argument;

    /**
     * Argument constructor
     *
     * @param string $argument Argument
     */
    public function __construct($argument)
    {
        $escaper = new Escaper();

        $this->argument = $escaper->argument($argument);
    }

    /**
     * Gets argument string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->argument;
    }
}