<?php

namespace App\Command;

use App\Repository\TaskRepository;
use App\Service\TelegramNotifier;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SendTodayTaskCommand extends Command
{
    protected $telegram;
    
    protected $taskRepository;

    public function __construct(TelegramNotifier $telegramNotifier, 
        TaskRepository $taskRepository)
    {
        $this->telegram = $telegramNotifier;
        $this->taskRepository = $taskRepository;

        parent::__construct();
    }

    protected static $defaultName = 'app:send:today-task';
    protected static $defaultDescription = 'This command send tasks to telegram';

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $messages = [];

        $tasks = $this->taskRepository->findTodayTasks();
        

        foreach ($tasks as $task ) {
            $messages[] = $task->getName();
        }

        $this->telegram->notify(
            sprintf("Todays task list: \n\r %s", implode("\n\r", $messages))
        );


        $io->success('Message sent');

        return Command::SUCCESS;
    }
}
