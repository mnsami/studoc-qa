<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class QAndA extends Command
{
    /**
     * String padding lenght used to show
     * test in cli
     *
     * @const integer
     */
    private const STRING_PADDING_LENGTH = 64;

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
    private const CMD_ADD = 'Add';

    /** @const string */
    private const CMD_VIEW = 'View';

    /** @const string */
    private const CMD_PRACTICE = 'Practice';

    /** @const string */
    private const CMD_QUIT = 'Quit';

    /** @const string */
    private const CMD_CANCEL = 'Cancel';

    /** @const array */
    private const MAIN_MENU_CHOICES = [
        self::CMD_ADD,
        self::CMD_VIEW,
        self::CMD_PRACTICE,
        self::CMD_QUIT,
        self::CMD_CANCEL
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->showMenu();
    }

    protected function showMenu()
    {
        $this->line($this->getBorderedStringWithPadding("=", "="));
        $this->line($this->getBorderedStringWithPadding(" ", " "));
        $this->info($this->getBorderedStringWithPadding(self::COMMAND_TITLE));
        $this->line($this->getBorderedStringWithPadding(" ", " "));
        $this->line($this->getBorderedStringWithPadding("***", " "));
        $this->line($this->getBorderedStringWithPadding(" ", " "));
        $this->line($this->getBorderedStringWithPadding("Menu"));
        $this->line($this->getBorderedStringWithPadding(" ", " "));
        $this->line($this->getBorderedStringWithPadding("To add new question/answer, type 'new / n'"));
        $this->line($this->getBorderedStringWithPadding("To view question/answer, type 'view / v'"));
        $this->line($this->getBorderedStringWithPadding("To view progress, type 'progress / p'"));
        $this->line($this->getBorderedStringWithPadding(" ", " "));
        $this->line($this->getBorderedStringWithPadding("***", " "));
        $this->line($this->getBorderedStringWithPadding(" ", " "));
        $this->info($this->getBorderedStringWithPadding("To you want to go back at anytime, type 'cancel / c' "));
        $this->info($this->getBorderedStringWithPadding("To you want to quit, type 'quit / q' "));
        $this->line($this->getBorderedStringWithPadding(" ", " "));
        $this->line($this->getBorderedStringWithPadding("=", "="));
    }

    /**
     * Get a RIGHT and LEFT padded string with
     * left and right border.
     *
     * @param string $string String to output
     * @param string $padding Optional. Specifies the string to use for padding. Default is whitespace
     *
     * @return string
     */
    private function getBorderedStringWithPadding($string, $padding = " ")
    {
        return "|" . $this->getPaddedStringForOutput($string, $padding) . "|";
    }

    /**
     * Get a RIGHT and LEFT padded string
     *
     * @param string $string String to output
     * @param string $padding Optional. Specifies the string to use for padding. Default is whitespace
     *
     * @return string
     */
    private function getPaddedStringForOutput($string, $padding = " ")
    {
        return str_pad($string, self::STRING_PADDING_LENGTH, $padding, STR_PAD_BOTH);
    }
}
