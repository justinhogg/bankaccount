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
use Cilex\Bank\Account;

class AccountOpenCommand extends Command
{
    const OPTION_BALANCE            = 'balance';
    const ARGUMENT_ACCOUNT_NUMBER   = 'accountNumber';
    const ARGUMENT_ACCOUNT_TYPE     = 'accountType';
    
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('account:open')
            ->setDescription('Open a new bank account.')
            ->addArgument(self::ARGUMENT_ACCOUNT_NUMBER, InputArgument::REQUIRED, 'Please enter an account number!')
            ->addArgument(self::ARGUMENT_ACCOUNT_TYPE, InputArgument::REQUIRED, 'Please enter an account type!')
            ->addOption(self::OPTION_BALANCE, null, InputOption::VALUE_NONE, 'If set, the balance will be shown');
        
    }
    
    /**
     * {@inheritDoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $defaultType = 'current';
        $question = array(
            "<comment>". Account::TYPE_CURRENT ."</comment>: Current Account\n",
            "<comment>". Account::TYPE_SAVINGS ."</comment>: Savings Account\n",
            "<question>Please choose an account type:</question> [<comment>$defaultType</comment>] ",
        );

        $accountType = $this->getHelper('dialog')->askAndValidate($output, $question, function($typeInput) {
            if (!in_array($typeInput, array(Account::TYPE_CURRENT, Account::TYPE_SAVINGS))) {
                throw new \InvalidArgumentException('Invalid type');
            }
            return $typeInput;
        }, 3, $defaultType);

        $input->setArgument(self::ARGUMENT_ACCOUNT_TYPE, $accountType);
        
        $accountNumber = $this->getHelper('dialog')->ask(
                $output,
                'Please create a new account number: '
        );
        
        $input->setArgument(self::ARGUMENT_ACCOUNT_NUMBER, $accountNumber);
    }
    
    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $accountType = $input->getArgument(self::ARGUMENT_ACCOUNT_TYPE);
        $accountNumber = $input->getArgument(self::ARGUMENT_ACCOUNT_NUMBER);
        
        $text = 'A new '. $accountType . ' account has been created with the account number ['. $accountNumber .']';
        
        $output->writeln($text);
    }
}
