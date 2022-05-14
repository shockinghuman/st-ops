<?php

namespace SH\STOPS\Commands;

use SH\STOPS\Operations\CheckURL;
use SH\STOPS\Operations\PingNet;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Check extends Command
{
    protected static $defaultDescription = 'Check domain availability';

    protected function configure()
    {
        $this
            ->addArgument('URL', InputArgument::OPTIONAL)
            ->addOption("daemon", "d", InputOption::VALUE_NONE, "Start pingnet daemon")
//            ->addOption("ip-address", "i", null, "Show the IP Address of domain")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $url = $input->getArgument('URL');
        // Check 1 specified URL
        if ($url){
            $check = CheckURL::checkOne($url);
            $result = "$check->url - $check->statusCode";
            $output->writeln($result);
            return Command::SUCCESS;
        }

        // Run once or start daemon
        $op = new PingNet($input, $output);
        if ($input->getOption("daemon")){
            $op->daemon();
        } else {
            $op->check();
        }

        return Command::SUCCESS;
    }

}