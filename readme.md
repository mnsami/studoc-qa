# Q/A app made with Laravel and the Artisan Console

## Requirements
- Docker
- docker-compose
- PHP >= 7.1

## Installation
- Clone the repository.
- Run `make init` to create local dotEnv files `.env` and `.docker.mysql.env` need to setup local dev environment.
- `make all` will bring the containers up and run `composer install`
- Edit `.docker.mysql.env` to set mysql credentials

## Usage
- `make qanda-interactive` to run Q and A cli command
- `make qanda-reset` to reset all answers and remove them from the database.

## `make` Commands
This is a set of `make` commands, I have a template for that I use for every project.
- `make lint`: to lint php, composer, eol
- `make phpcs`: to run php code sniffer utility and check code style according to PSR-2
- `make phpcbf`: to fix all php code styles
- `make container-up`: start docker containers
- `make tear-down`: stop, down and remove all containers
- `make composer-install`: to run composer install

## Architectural desicions
**Disclaimer**: Using laravel as a framework is relatively new to me, I consider myself intermediate, I'm more experienced towards Symfony.

### Separation of concerns
As a common practice to separation of concerns. I tried as much as possible to follow this pattern, but also try to be true the framework.
So, after some research and reading, I decided to follow this blog [post](https://lorisleiva.com/conciliating-laravel-and-ddd-part-2/), as I found it was a common way to do things in laravel. 

### CQRS
I tried to follow the CQRS pattern by creating several commands and handlers which live in `Services` folder.

This helped me isolate the logic and follow single responsibility services.

### Dividing commands
I decided to separate each process into its own artisan command. This game an advantage to focus on the business process
and not write too much logic in one artisan command and make it hold more responsibilities than it should do.

### Sub-commands vs Events
Since it is mentioned that the command should run an event loop. I had two choices:
1. Use mix of commands and events, then write event listeners for example to handle user input or even run sub-commands.
2. Use sub-commands, by that I mean have a main command `qanda:interactive` and call sub-commands programmatically based on the user choice.

I decided to go with option 2 and that is because:
- Simplicity of the task, I don't like to over complicate things.
- I believed it is simpler than adding another layer.

### Ideas for improvements
- User different models from the Eloquent models. 


## Problem description

The purpose of the exercise is to see how comfortable you are with a Laravel based interactive console app. We have done a bit of work for you. If you fork this project, run `composer install`, and then run `php artisan qanda:interactive`, the command will be started. In this command, create an event loop and implement the following features:

- The initial interaction should allow you to choose between adding questions and answers and viewing previously entered answers.

### Creating Questions
- Upon choosing the option to add a question, the user will be prompted to give a question and the answer to that question.
- Upon giving a question and answer, this must be stored in the database. Use migrations to create the DB tables.

### Practising Questions
- Upon choosing to view the questions, the user will be prompted to choose from the previously given questions which one he wants to practice.
- Upon choosing to practice a question, the user must fill in the right answer for the question, which will be checked against the previously given answer.
- Upon answering a question, the user is returned to the list of all questions, and sees his progress for each question.
- Upon completing all questions, an overview of the users final progress is given.

### Extra
- Every step must have an option to go back one step.
- Use the DB, and use laravel best practices to approach it.
- Allow the user to exit the interactive console with an option at every point.

### I really want this job
- Make a new console command to be run with `php artisan qanda:reset` that removes all previous progresses.
- Write (a) unit test(s).

