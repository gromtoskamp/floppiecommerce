<?php

/**
 * How to CLI:
 *
 * TODO: write a line
 * TODO: clean a line
 */
namespace Cli\Model;

use Object\Model\Object;

class Cli extends Object
{
    /**
     * Cli constructor.
     */
    public function __construct(
    ) {
        parent::__construct();
    }

    /**
     * Creates a new line.
     *
     * @return $this
     */
    public function newLine()
    {
        echo PHP_EOL;
        return $this;
    }

    /**
     * Write text on the current line.
     *
     * @param $text
     * @return $this
     */
    public function write($text)
    {
        echo $text;
        return $this;
    }

    /**
     * Writes text on the current line and
     * creates a new line.
     *
     * @param $text
     * @return $this
     */
    public function writeLine($text)
    {
        echo $text;
        $this->newLine();
        return $this;
    }

    /**
     * Cleans the current line
     */
    public function clean()
    {
        echo "\r";

        /**
         * Print empty line
         */
        exec("echo $(tput cols)", $length);
        for ($i=0; $i < $length[0]; $i++) {
            echo " ";
        }

        echo "\r";
        return $this;
    }

    /**
     * Reads input from command line.
     *
     * @return string
     */
    public function read()
    {
        $stream = fopen("php://stdin", "r");
        $value = fgets($stream);
        return trim($value);
    }

    /**
     * Reads the first character of user defined input.
     *
     * @return string
     */
    public function readCharacter()
    {
        $stream = fopen("php://stdin", "r");
        $value = fgetc($stream);
        return trim($value);
    }

    /**
     * Wait for a keystroke to continue
     *
     * @return $this
     */
    public function waitForKey()
    {
        passthru('read -rsn1');
        return $this;
    }


}