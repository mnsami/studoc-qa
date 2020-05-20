<?php

namespace App\Console\Commands;

use App\Console\InteractiveConsoleCommand;

class QAndA extends InteractiveConsoleCommand
{
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
    private const CMD_ADD_NEW_SHORT = 'n';
    private const CMD_ADD_CHOICE = 'Add New Question';

    /** @const string */
    private const CMD_PRACTICE = 'practice';
    private const CMD_PRACTICE_SHORT = 'p';
    private const CMD_PRACTICE_CHOICE = 'Practice questions';

    /** @const string */
    private const CMD_PROGRESS = 'progress';
    private const CMD_PROGRESS_SHORT = 'g';
    private const CMD_PROGRESS_CHOICE = 'Show your progress.';

    /** @const array */
    private const MAIN_MENU_CHOICES = [
        self::CMD_ADD_NEW => self::CMD_ADD_CHOICE,
        self::CMD_PRACTICE => self::CMD_PRACTICE_CHOICE,
        self::CMD_PROGRESS => self::CMD_PROGRESS_CHOICE
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

            $choice = strtolower(
                $this->choice(
                    'Choose from the menu below',
                    $this->choices()
                )
            );

            switch ($choice) {
                case self::CMD_ADD_NEW:
                case self::CMD_ADD_NEW_SHORT:
                    $this->handleSubCommandExitCodes($this->call('qanda:add-new-question'));
                    break;
                case self::CMD_PRACTICE:
                case self::CMD_PRACTICE_SHORT:
                    $this->handleSubCommandExitCodes($this->call('qanda:practice-questions'));
                    break;
                default:
                    $this->handleCommonInputChoice($choice);
                    break;
            }
        }

        return self::SUCCESS_EXIT_CODE;
    }

    protected function commandChoices(): array
    {
        return self::MAIN_MENU_CHOICES;
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
        $this->line($this->writePaddedStringWithLeftRightBorders("=", "="));
    }
}
