<?php

declare(strict_types=1);

namespace App\Command;

use App\UseCase\ProcessMusic\ProcessMusicHandler;
use Doctrine\DBAL\Exception;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'test_call',
)]
class ProcessMusicCommand extends Command
{
    public string $name = 'test2';

    public function __construct(private readonly ProcessMusicHandler $handler)
    {
        parent::__construct('sdsdds');
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     * @throws JsonException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->handler->handle();

        return Command::SUCCESS;
    }
}
