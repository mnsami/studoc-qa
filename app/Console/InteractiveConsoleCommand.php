<?php
declare(strict_types=1);

namespace App\Console;
use Illuminate\Console\Command;

abstract class InteractiveConsoleCommand extends Command
{
    use ConsoleStringFormatter;

    /** @var bool */
    protected $running;

    /** @const string */
    protected const CMD_QUIT = 'quit';
    protected const CMD_QUIT_SHORT = 'q';

    /** @const string */
    protected const CMD_CANCEL = 'cancel';
    protected const CMD_CANCEL_SHORT = 'c';

    protected const CANCEL_CHOICES = [
        self::CMD_CANCEL,
        self::CMD_CANCEL_SHORT
    ];

    protected const QUIT_CHOICES = [
        self::CMD_QUIT,
        self::CMD_QUIT_SHORT
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
     * InteractiveConsoleCommand constructor.
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
        if ($this->confirm("Are you sure you want to quit?")) {
            $this->running = false;
        }
    }

    protected function handleSubCommand(int $exitCode)
    {
        if (self::TERMINATE_EXIT_CODE === $exitCode) {
            $this->quit();
        }
    }
}
