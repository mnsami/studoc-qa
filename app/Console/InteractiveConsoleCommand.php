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
    protected const CMD_QUIT_CHOICE = 'Quit application.';

    /** @const string */
    protected const CMD_CANCEL = 'cancel';
    protected const CMD_CANCEL_SHORT = 'c';
    protected const CMD_CANCEL_CHOICE = 'Cancel and go back to menu.';

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
    protected const QUIT_EXIT_CODE = 130;

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
    protected function confirmQuit(): bool
    {
        if ($this->confirm("Are you sure you want to quit?")) {
            return true;
        }

        return false;
    }

    protected function quit(): void
    {
        $this->running = false;
    }

    /**
     * @param int $exitCode
     * @return int
     */
    protected function handleSubCommandExitCodes(int $exitCode): int
    {
        if (self::QUIT_EXIT_CODE === $exitCode) {
            $this->quit();
        }

        if (self::CANCEL_EXIT_CODE === $exitCode) {
            return $exitCode;
        }

        return self::SUCCESS_EXIT_CODE;
    }

    /**
     * @return array|string[]
     */
    private function commonChoices(): array
    {
        return [
            self::CMD_CANCEL => self::CMD_CANCEL_CHOICE,
            self::CMD_QUIT => self::CMD_QUIT_CHOICE
        ];
    }

    /**
     * Command specific choices
     * @return array
     */
    protected abstract function commandChoices(): array;

    /**
     * @return array|string[]
     */
    protected function choices(): array
    {
        return $this->commandChoices() + $this->commonChoices();
    }

    /**
     * @param string $choice
     * @return int
     */
    protected function handleTerminationInputChoice(string $choice): int
    {
        switch ($choice) {
            case self::CMD_QUIT_SHORT:
            case self::CMD_QUIT:
                if ($this->confirmQuit()) {
                    $this->quit();
                    return self::QUIT_EXIT_CODE;
                }
                return self::CANCEL_EXIT_CODE;
                break;
            case self::CMD_CANCEL:
            case self::CMD_CANCEL_SHORT:
                return self::CANCEL_EXIT_CODE;
                break;
            case self::ERROR_EXIT_CODE:
                return self::ERROR_EXIT_CODE;
                break;
        }

        return self::CANCEL_EXIT_CODE;
    }
}
