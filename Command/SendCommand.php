<?php

namespace DoS\SMSBundle\Command;

use SmsSender\Exception\WrappedException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SendCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('dos:sms:send')
            ->setDescription('Send sms.')
            ->addArgument('number', InputArgument::REQUIRED, 'Which number you want to send to?')
            ->addArgument('message', InputArgument::REQUIRED, 'What is message you want to send?')
            ->addOption('originator', 'o', InputOption::VALUE_OPTIONAL, 'Originator required for some provider.', 'DoS')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $number = $input->getArgument('number');
        $message = $input->getArgument('message');
        $originator = $input->getOption('originator');

        $sender = $this->getContainer()->get('dos.sms.sender');

        try {
            $result = $sender->send($number, $message, $originator);
        } catch (WrappedException $e) {
            $result = $e->getWrappedException()->getMessage();
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }

        $output->writeln(($result));
    }
}
