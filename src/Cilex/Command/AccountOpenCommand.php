<?php
/**
 * Description of AccountOpenCommand
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
use Cilex\Bank\CurrentAccount;

class AccountOpenCommand extends Command
{
    const OPTION_BALANCE            = 'balance';
    const ARGUMENT_ACCOUNT_NUMBER   = 'accountNumber';
    const ARGUMENT_ACCOUNT_TYPE     = 'accountType';
    const ARGUMENT_DEPOSIT_AMOUNT   = 'depositAmount';
    
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
            ->addArgument(self::ARGUMENT_DEPOSIT_AMOUNT, InputArgument::OPTIONAL, 'Please enter the deposit amount!')
            ->addOption(self::OPTION_BALANCE, null, InputOption::VALUE_NONE, 'If set, the balance will be shown');
        
    }
    
    /**
     * {@inheritDoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        //account type
        $input->setArgument(self::ARGUMENT_ACCOUNT_TYPE, $this->getAccountType($output));
        
        //account number
        $input->setArgument(self::ARGUMENT_ACCOUNT_NUMBER, $this->getAccountNumber($output));
        
        //amount to deposit
        if ($this->getHelper('dialog')->askConfirmation(
            $output,
            '<question>Would you like to deposit some money?</question>',
            false
        )) {
            $depositAmount = $this->getHelper('dialog')->ask(
                $output,
                'Enter the amount you would like to deposit: '
            );
            
            $input->setArgument(self::ARGUMENT_DEPOSIT_AMOUNT, $depositAmount);
            return;
        }
    }
    
    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $accountType    = $input->getArgument(self::ARGUMENT_ACCOUNT_TYPE);
        $accountNumber  = $input->getArgument(self::ARGUMENT_ACCOUNT_NUMBER);
        $depositAmount  = $input->getArgument(self::ARGUMENT_DEPOSIT_AMOUNT);
        
        //create a new account
        switch($accountType) {
            case Account::TYPE_CURRENT:
                $account = new CurrentAccount($accountNumber);
                break;
        }
        
        //TODO check if account exists and handle accordingly
        $account->open();
        
        //if a deposit has been made
        ($depositAmount) ? $account->deposit((double) $depositAmount): false;
        
        //output text
        $text = 'A new '. $accountType . ' account has been created with the account number ['. $accountNumber .'].';
        
        //if the option 'balance'  is requested give the account balance.
        ($input->getOption(self::OPTION_BALANCE)) ? 
            $text .= 'The account has a balance of '. $account->getBalance(). '.': false;
        
        $output->writeln($text);
    }
    
    protected function getAccountType($output)
    {
        //set up the bank account
        $defaultType = 'current';
        $question = array(
            "<comment>". Account::TYPE_CURRENT ."</comment>: Current Account\n",
            "<question>Please choose an account type:</question> [<comment>$defaultType</comment>] ",
        );

        $accountType = $this->getHelper('dialog')->askAndValidate($output, $question, function($typeInput) {
            if (!in_array($typeInput, array(Account::TYPE_CURRENT))) {
                throw new \InvalidArgumentException('Invalid type');
            }
            return $typeInput;
        }, 3, $defaultType);
        
        return $accountType;
    }
    
    protected function getAccountNumber($output)
    {
        $accountNumber = $this->getHelper('dialog')->ask(
                $output,
                'Please create a new account number: '
        );
        
        return $accountNumber;
    }
}
