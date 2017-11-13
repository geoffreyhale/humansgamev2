<?php

namespace OneThirtyWordsBundle\Command;

use OneThirtyWordsBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendEmailCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('send-email')
            ->setDescription('Sends e-mail to users.')
            ->setHelp('This command is for sending a daily reminder e-mail to users who have opted in.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $users = $em->getRepository(User::class)->findBy([
            'enabled' => true,
            'emailReminders' => true,
        ]);

        foreach ($users as $user) {
            if ($this->getContainer()->get('user_service')->getUserWordCountTodayByUser($user) < 130) {
                $displayName = $user->getDisplayName() ? $user->getDisplayName() : $user->getUsername();
                $email = $user->getEmail();
                $to = [$email => $displayName];

                $message = (new \Swift_Message())
                    ->setSubject('130 Words Reminder')
                    ->setFrom(['support@130words.com' => '130 Words'])
                    ->setTo($to)
                    ->setBody(
                        $this->getContainer()->get('templating')->render(
                            'OneThirtyWordsBundle:Email:reminder.html.twig',
                            array('name' => $displayName)
                        ),
                        'text/html'
                    )
                ;

                if ($this->getContainer()->get('mailer')->send($message)) {
                    $output->writeln('Swift Mailer sent message to ' . $to);
                } else {
                    $output->writeln('Swift Mailer failed to send message to ' . $to);
                }
            }
        }
    }
}