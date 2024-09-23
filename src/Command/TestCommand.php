<?php

declare(strict_types=1);

namespace App\Command;

use App\UseCase\Spotify\SpotifyHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'test_call',
)]
class TestCommand extends Command
{
    public string $name = 'test2';

    public function __construct(private readonly SpotifyHandler $handler)
    {
        parent::__construct('sdsdds');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->handler->handle();
        } catch (\Throwable $e) {
            dd($e);
        }

        return 1;
    }
}
