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

        $message = (new \Swift_Message('Daily Reminder Email from 130words.com'))
            ->setFrom('support@130words.com')
            ->setTo('geoffreyhale@gmail.com')
            ->setBody(
                $this->getContainer()->get('templating')->render(
                    'OneThirtyWordsBundle:Email:test.html.twig',
                    array('name' => 'ThisIsMyTestName')
                ),
                'text/html'
            )
        ;

        $this->getContainer()->get('mailer')->send($message);

        $output->writeln('Ending Send Email Command');
    }
}