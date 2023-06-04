<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:info',
    description: 'Application information',
)]
class InfoCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setHelp(<<<'HELP'
<info>First Law</info>
A robot may not injure a human being or, through inaction, allow a human being to come to harm.
<info>Second Law</info>
A robot must obey the orders given it by human beings except where such orders would conflict with the First Law.
<info>Third Law</info>
A robot must protect its own existence as long as such protection does not conflict with the First or Second Law.
HELP
            )
            ->addOption('v', null, InputOption::VALUE_NONE, 'Verbose')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $result = $this->checkLicence();

        if ($input->getOption('v')) {
            $io->writeln(sprintf("Credits: %s", 'default'));
        }

        $io->success('Your copy is legal');

        return $result;
    }

    private function checkLicence() : int
    {
        return Command::SUCCESS;
    }
}
