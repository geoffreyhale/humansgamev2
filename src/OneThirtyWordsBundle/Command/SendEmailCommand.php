<?php

namespace OneThirtyWordsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendEmailCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('send-email')

            // the short description shown while running "php bin/console list"
            ->setDescription('Sends e-mail to users.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command is for sending a daily reminder e-mail to users who have opted in.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $from = 'support@130words.com';

        $to = 'geoffreyhale@gmail.com';
        $subject = '130 Words';
        $body = "Hi!\r\n\r\n  Don't forget to write 130 words today!\r\n\r\n  Link: 130words.com\r\n\r\n  Write on,\r\n  130 Words Support Team";
        $headers = "From: $from" . "\r\n" .
            "Reply-To: $from" . "\r\n" .
            "X-Mailer: PHP/" . phpversion()
        ;

        if (mail($to, $subject . " (mail)", $body, $headers)) {
            $output->writeln('mail success');
        } else {
            $output->writeln('mail failed');
        }

//        $mailLogger = new \Swift_Plugins_Loggers_ArrayLogger();
//        $this->getContainer()->get('mailer')->registerPlugin(new \Swift_Plugins_LoggerPlugin($mailLogger));
//
        $message = (new \Swift_Message($subject . " (Swift Mailer)"))
            ->setFrom($from)
            ->setTo($to)
            ->setBody(
                $body
//                $this->getContainer()->get('templating')->render(
//                    'OneThirtyWordsBundle:Email:test.html.twig',
//                    array('name' => 'ThisIsMyTestName')
//                ),
//                'text/html'
            )
        ;

        if ($this->getContainer()->get('mailer')->send($message)) {
            $output->writeln('Swift Mailer sent message to ' . $to);
        } else {
            $output->writeln('Swift Mailer failed to send message to ' . $to);
//            $mailLogger->dump();
        }
    }
}