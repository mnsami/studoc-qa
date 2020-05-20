<?php

namespace App\Providers;

use App\Domain\Question\Model\AnswerRepository;
use App\Domain\Question\Model\QuestionRepository;
use App\Infrastructure\Persistence\EloquentAnswerRepository;
use App\Infrastructure\Persistence\EloquentQuestionRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            QuestionRepository::class,
            function (): QuestionRepository {
                return new EloquentQuestionRepository();
            }
        );

        $this->app->bind(
            AnswerRepository::class,
            function (): AnswerRepository {
                return new EloquentAnswerRepository();
            }
        );
    }
}
