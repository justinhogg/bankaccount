<?php
/**
 * Description of AccountCommand
 *
 * @author jhogg
 */

namespace Cilex\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Cilex\Provider\Console\Command;

class AccountCommand extends Command
{
    const OPTION_OPEN   = 'open';
    const OPTION_CLOSE  = 'close';
    
    
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('bank:account')
            ->setDescription('Manage a bank account.')
            ->addOption(self::OPTION_OPEN, null, InputOption::VALUE_NONE, 'If set, a new account will be opened')
            ->addOption(self::OPTION_CLOSE, null, InputOption::VALUE_NONE, 'If set, an account will be closed');
        
    }
    
    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $messages = array();
        
        //$accountType = $input->getArgument('accountType');
        
        if ($input->getOption(self::OPTION_OPEN)) {
            $messages[] = 'Account was opened';
        }
        
        if ($input->getOption(self::OPTION_CLOSE)) {
            $messages[] = 'Account was closed';
        }

        $output->writeln($messages);
    }
}
