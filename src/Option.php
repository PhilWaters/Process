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
class Option
{
    /**
     * Stores option
     *
     * @var string
     */
    private $option;

    /**
     * Stores option value
     *
     * @var null|string
     */
    private $value;

    /**
     * Stores option separator (space or =)
     *
     * @var string
     */
    private $separator;

    /**
     * Option constructor
     *
     * @param string $option    Option
     * @param string $value     Value
     * @param string $separator Separator
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($option, $value = null, $separator = " ")
    {
        if (!$this->validate($option)) {
            throw new \InvalidArgumentException("$option is not a valid option");
        }

        if ($value !== null) {
            $escaper = new Escaper();

            $value = $escaper->argument($value);
        }

        $this->option = $option;
        $this->value = $value;
        $this->separator = $separator == "=" ? "=" : " ";
    }

    /**
     * Gets option string
     *
     * @return string
     */
    public function __toString()
    {
        $result = $this->option;

        if ($this->value === null) {
            $result .= $this->separator . $this->value;
        }

        return $result;
    }

    /**
     * Validates an option
     *
     * @param string $option Option string
     *
     * @return boolan True, if option is valid, else, false
     */
    private function validate($option)
    {
        return preg_match("/^\\-{1,2}[a-z_-]$/i", $option) === 1;
    }
}