<?php
/**
 * Description of AccountOpenCommand
 *
 * @author Justin Hogg <justin@thekordas.com>
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
    const OPTION_OVERDRAFT          = 'overdraft';
    const ARGUMENT_ACCOUNT_NUMBER   = 'accountNumber';
    const ARGUMENT_ACCOUNT_TYPE     = 'accountType';
    const ARGUMENT_DEPOSIT_AMOUNT   = 'depositAmount';
    const ARGUMENT_WITHDRAW_AMOUNT  = 'withdrawAmount';
    const ARGUMENT_OVERDRAFT_AMOUNT = 'overdraftAmount';
    
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
            ->addArgument(self::ARGUMENT_WITHDRAW_AMOUNT, InputArgument::OPTIONAL, 'Please enter the amount you would like to withdraw!')
            ->addArgument(self::ARGUMENT_OVERDRAFT_AMOUNT, InputArgument::OPTIONAL, 'Please enter the overdraft amount!')
            ->addOption(self::OPTION_OVERDRAFT, null, InputOption::VALUE_NONE, 'If set, the overdraft limit will be shown')
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
        
        //offer overdraft to certain accounts
        switch ($input->getArgument(self::ARGUMENT_ACCOUNT_TYPE)) {
            case Account::TYPE_CURRENT:
                //amount to set overdraft
                $this->setOverdraft($input, $output);
                break;
        }
        //amount to deposit
        $this->setDeposit($input, $output);
        
        //amount to withdraw
        $this->setWithdraw($input, $output);
    }
    
    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = array();
        
        $accountType    = $input->getArgument(self::ARGUMENT_ACCOUNT_TYPE);
        $accountNumber  = $input->getArgument(self::ARGUMENT_ACCOUNT_NUMBER);
        $depositAmount  = money_format('%.2n', $input->getArgument(self::ARGUMENT_DEPOSIT_AMOUNT));
        $overdraftAmount= money_format('%.2n', $input->getArgument(self::ARGUMENT_OVERDRAFT_AMOUNT));
        $withdrawAmount = money_format('%.2n', $input->getArgument(self::ARGUMENT_WITHDRAW_AMOUNT));
        
        //create a new account
        switch($accountType) {
            case Account::TYPE_CURRENT:
                $account = new CurrentAccount($accountNumber);
                break;
        }
        
        //TODO check if account exists and handle accordingly
        $account->open();
        //output text
        $text[] = 'A new '. $accountType . ' account has been created with the account number ['. $accountNumber .'].';
        
        //if a deposit has been made
        if ($depositAmount && $depositAmount > 0) {
            $account->deposit((double) $depositAmount);
            $text[] = 'A deposit of '. $depositAmount . ' was made.';
        }
        
        //if overdraft is required
        if ($overdraftAmount && $overdraftAmount > 0) {
            $account->setOverdraft(new \Cilex\Bank\OverdraftService($account), (double) $overdraftAmount);
            $text[] = 'An overdraft of '. $overdraftAmount . ' was set.';
        }
        
        //if a withdraw is made
        if ($withdrawAmount && $withdrawAmount > 0) {
            $account->withdraw((double) $withdrawAmount);
            $text[] = 'A withdraw of '. $withdrawAmount . ' was made.';
        }

        //if the option 'overdraft'  is requested give the overdraft status.
        ($input->getOption(self::OPTION_OVERDRAFT)) ? 
            $text[] = 'The account has an overdraft limit of '. $account->getOverdraftLimit(). '.': false;
        
        //if the option 'balance'  is requested give the account balance.
        ($input->getOption(self::OPTION_BALANCE)) ? 
            $text[] = 'The account has a balance of '. $account->getBalance(). '.'.PHP_EOL: false;
        
        //output
        $output->writeln($text);
    }
    
    /**
     * getAccountType - interaction to establish the different account types
     *
     * @param OutputInterface $output
     * @return string
     */
    protected function getAccountType(OutputInterface $output)
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
    
    /**
     * getAccountNumber - interaction to establish the account number
     *
     * @param OutputInterface $output
     * @return string
     */
    protected function getAccountNumber(OutputInterface $output)
    {
        $accountNumber = $this->getHelper('dialog')->ask(
                $output,
                'Please create a new account number: '
        );
        
        return $accountNumber;
    }
    
    /**
     * setDeposit - interaction to set deposit amount
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return double
     */
    protected function setDeposit(InputInterface $input, OutputInterface $output)
    {
        if ($this->getHelper('dialog')->askConfirmation(
            $output,
            '<question>Would you like to deposit some money?</question>',
            false
        )) {
            $amount = $this->getHelper('dialog')->ask(
                $output,
                'Enter the amount you would like to deposit: '
            );
            
            $input->setArgument(self::ARGUMENT_DEPOSIT_AMOUNT, $amount);
            return;
        }
    }
    
    /**
     * setOverdraft - interaction to set overdraft amount
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return double
     */
    protected function setOverdraft(InputInterface $input, OutputInterface $output)
    {
        if ($this->getHelper('dialog')->askConfirmation(
            $output,
            '<question>Would you like to enable an overdraft for this account?</question>',
            false
        )) {
            $amount = $this->getHelper('dialog')->ask(
                $output,
                'Enter the overdraft limit you would like: '
            );
            
            $input->setArgument(self::ARGUMENT_OVERDRAFT_AMOUNT, $amount);
            return;
        }
    }
    
    /**
     * setwithdraw - interaction to set withdraw amount
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return double
     */
    protected function setWithdraw(InputInterface $input, OutputInterface $output)
    {
        if ($this->getHelper('dialog')->askConfirmation(
            $output,
            '<question>Would you like to withdraw?</question>',
            false
        )) {
            $amount = $this->getHelper('dialog')->ask(
                $output,
                'Enter the amount you would like withdraw: '
            );
            
            $input->setArgument(self::ARGUMENT_WITHDRAW_AMOUNT, $amount);
            return;
        }
    }
}
