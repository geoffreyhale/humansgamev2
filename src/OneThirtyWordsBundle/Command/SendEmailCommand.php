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
        $output->writeln('Executing Send Email Command');

//        $mailLogger = new \Swift_Plugins_Loggers_ArrayLogger();
//        $this->getContainer()->get('mailer')->registerPlugin(new \Swift_Plugins_LoggerPlugin($mailLogger));
//
        $toEmail = 'geoffreyhale@gmail.com';
//
//        $message = (new \Swift_Message('Daily Reminder Email from 130words.com'))
//            ->setFrom('support@130words.com')
//            ->setTo($toEmail)
//            ->setBody(
//                $this->getContainer()->get('templating')->render(
//                    'OneThirtyWordsBundle:Email:test.html.twig',
//                    array('name' => 'ThisIsMyTestName')
//                ),
//                'text/html'
//            )
//        ;
//
//        if ($this->getContainer()->get('mailer')->send($message)) {
//            $output->writeln('Mailer sent message to ' . $toEmail);
//        } else {
//            $output->writeln('Mailer failed to send message to ' . $toEmail);
//            $mailLogger->dump();
//        }

        $subject = 'Have you written 130 words today?';
        $message = 'This is a friendly reminder to write at 130words.com.';
        $headers = 'From: support@130words.com' . "\r\n" .
            'Reply-To: support@130words.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        if (mail($toEmail, $subject, $message, $headers)) {
            $output->writeln('mail success');
        } else {
            $output->writeln('mail failed');
        }

        $output->writeln('Ending Send Email Command');
    }
}