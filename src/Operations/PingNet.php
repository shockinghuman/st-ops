<?php

namespace SH\STOPS\Operations;

use Symfony\Component\Console\Command\Command;

class PingNet
{
    protected static $input;
    protected static $output;

    public function __construct($input, $output)
    {
        self::$input = $input;
        self::$output = $output;
    }

    public static function check()
    {
        $output = self::$output;
//        Check url list once
        $output->writeln('INFO: Checking URLs');
        if (!Config::return('url_list'))
        {
            $output->writeln('WARNING: No config.php found');
            return Command::FAILURE;
        }
        if (!file_exists(Config::return('url_list')))
        {
            File::add_line(Config::return('url_list'), 'google.com');
            $output->writeln('URL List created at '.Config::return('url_list'));
        }
        $results = CheckURL::checkMany(file(Config::return('url_list')));
        $error_messages = [];
        $error_report = '';

        foreach ($results as $result){
            if ($result->statusCode !== 200){
                $error_messages[] = "$result->statusCode - $result->url";
                $error_report .= "$result->statusCode - $result->url\n";
            }
        }

//        Report Errors
        if (count($error_messages) > 0){
//            By Screen
            $output->writeln(count($error_messages)." ERRORS:");
            $output->write($error_messages);
            $output->writeln('');
            $output->writeln('INFO: Reporting Errors');
        } else {
            $output->writeln("INFO: Everything is ok!");
        }
    }

    public static function daemon()
    {
        while(true){
//            Check URLs
            self::check();
            sleep(300);
        }
    }
}