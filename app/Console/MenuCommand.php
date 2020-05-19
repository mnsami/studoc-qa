<?php
declare(strict_types=1);

namespace App\Console;
use Illuminate\Console\Command;

abstract class MenuCommand extends Command
{
    /** @var bool */
    protected $running;

    /** @const string */
    protected const CMD_QUIT = 'quit';

    /** @const string */
    protected const CMD_CANCEL = 'cancel';

    protected const CANCEL_CHOICES = [
        self::CMD_CANCEL
    ];

    protected const QUIT_CHOICES = [
        self::CMD_QUIT
    ];

    /**
     * Success exit code
     *
     * @const int
     */
    protected const SUCCESS_EXIT_CODE = 0;

    /**
     * Error exit code
     *
     * @const int
     */
    protected const ERROR_EXIT_CODE = 1;

    /**
     * Quit exit code
     *
     * @const int
     */
    protected const TERMINATE_EXIT_CODE = 130;

    /**
     * Quit exit code
     *
     * @const int
     */
    protected const CANCEL_EXIT_CODE = 200;

    /**
     * MenuCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->running = false;
    }

    /**
     * Print Menu for command
     */
    protected abstract function showMenu(): void;

    /**
     * Checks if user input to cancel
     *
     * @param string $input
     * @return bool
     */
    protected function shouldCancel(string $input): bool
    {
        if (in_array($input, self::CANCEL_CHOICES, true)) {
            return true;
        }

        return false;
    }

    /**
     * Checks if user input to quit
     *
     * @param string $input
     * @return bool
     */
    protected function shouldQuit(string $input): bool
    {
        if (in_array($input, self::QUIT_CHOICES, true)) {
            return true;
        }

        return false;
    }

    /**
     * Breaks the running loop
     */
    protected function quit(): void
    {
        $this->running = false;
    }
}
