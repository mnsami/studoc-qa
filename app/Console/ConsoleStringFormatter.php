<?php
declare(strict_types=1);

namespace App\Console;

trait ConsoleStringFormatter
{
    /**
     * Format a RIGHT and LEFT padded string
     *
     * @param string $string String to output
     * @param string $padding Optional. Specifies the string to use for padding. Default is whitespace
     *
     * @return string
     */
    public function writePaddedString(string $string, string $padding = " "): string
    {
        return str_pad($string, 64, $padding, STR_PAD_BOTH);
    }

    /**
     * Format a RIGHT and LEFT padded string with
     * left and right border.
     *
     * @param string $string String to output
     * @param string $padding Optional. Specifies the string to use for padding. Default is whitespace
     *
     * @return string
     */
    public function writePaddedStringWithLeftRightBorders(string $string, string $padding = " ", string $border = '|'): string
    {
        return $border . $this->writePaddedString($string, $padding) . $border;
    }
}
