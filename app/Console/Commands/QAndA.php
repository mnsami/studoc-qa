<?php

namespace App\Console\Commands;

use App\Console\ConsoleStringFormatter;
use App\Console\InteractiveConsoleCommand;

class QAndA extends InteractiveConsoleCommand
{
    use ConsoleStringFormatter;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:interactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs an interactive command line based Q And A system.';

    /** @const string  */
    private const COMMAND_TITLE = "Welcome to interactive command line based Q And A system";

    /** @const string */
    private const CMD_ADD_NEW = 'new';

    /** @const string */
    private const CMD_VIEW = 'view';

    /** @const string */
    private const CMD_PRACTICE = 'practice';

    /** @const array */
    private const MAIN_MENU_CHOICES = [
        self::CMD_ADD_NEW,
        self::CMD_VIEW,
        self::CMD_PRACTICE,
        self::CMD_QUIT,
        self::CMD_CANCEL
    ];

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->running = true;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        while ($this->running) {

            $this->showMenu();

            $command = strtolower(
                $this->anticipate(
                    'Choose from the menu above',
                    self::MAIN_MENU_CHOICES
                )
            );

            switch ($command) {
                case self::CMD_QUIT:
                    $this->quit();
                    break;
                case self::CMD_ADD_NEW:
                    $this->handleSubCommand($this->call('qanda:add-new-question'));
                    break;
            }

        }

        return self::SUCCESS_EXIT_CODE;
    }

    /**
     * @inheritDoc
     */
    protected function showMenu(): void
    {
        $this->line($this->writePaddedStringWithLeftRightBorders("=", "="));
        $this->line($this->writePaddedStringWithLeftRightBorders(" ", " "));
        $this->info($this->writePaddedStringWithLeftRightBorders(self::COMMAND_TITLE));
        $this->line($this->writePaddedStringWithLeftRightBorders(" ", " "));
        $this->line($this->writePaddedStringWithLeftRightBorders("***", " "));
        $this->line($this->writePaddedStringWithLeftRightBorders(" ", " "));
        $this->line($this->writePaddedStringWithLeftRightBorders("Menu"));
        $this->line($this->writePaddedStringWithLeftRightBorders(" ", " "));
        $this->line($this->writePaddedStringWithLeftRightBorders("To add new question/answer, type 'new / n'"));
        $this->line($this->writePaddedStringWithLeftRightBorders("To view question/answer, type 'view / v'"));
        $this->line($this->writePaddedStringWithLeftRightBorders("To view progress, type 'progress / p'"));
        $this->line($this->writePaddedStringWithLeftRightBorders(" ", " "));
        $this->line($this->writePaddedStringWithLeftRightBorders("***", " "));
        $this->line($this->writePaddedStringWithLeftRightBorders(" ", " "));
        $this->info($this->writePaddedStringWithLeftRightBorders("To you want to go back at anytime, type 'cancel / c' "));
        $this->info($this->writePaddedStringWithLeftRightBorders("To you want to quit, type 'quit / q' "));
        $this->line($this->writePaddedStringWithLeftRightBorders(" ", " "));
        $this->line($this->writePaddedStringWithLeftRightBorders("=", "="));
    }
}
