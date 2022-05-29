<?php

namespace SH\STOPS\Commands;

use SH\STOPS\Operations\Config;
use SH\STOPS\Operations\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class URLList extends Command
{
    protected static $defaultDescription = 'Edit URL List';

    protected function configure()
    {
        $this->addArgument('action', InputArgument::REQUIRED, 'add, remove, show')
        ->addArgument('url', InputArgument::OPTIONAL, 'URL to operate on');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $action = $input->getArgument('action');
        $url = $input->getArgument('url');
        $url_list = Config::return('url_list');

        switch ($action)
        {
            case 'add':
                File::add_line($url_list, $url);
                $output->writeln("$url added to URL List");
                break;
            case 'remove':
                File::rm_line($url_list, $url);
                $output->writeln("$url removed from URL List");
                break;
            case 'show':
                if (!file_exists($url_list)){
                    $output->writeln('INFO: No url in list');
                } else {
                    $output->write(\file($url_list));
                    $output->writeln('');
                }

                break;
            default:
                return Command::INVALID;
        }
        return Command::SUCCESS;
    }
}