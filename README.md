# bankaccount
A simple command line interface bank account

### Installation

git clone this repository.

Download composer: curl -s https://getcomposer.org/installer | php

Install dependencies, run this command from the root directory:

```php composer.phar install```

### Commands

*account*

__account:open__ - Open a new bank account. Set an overdraft. Deposit and withdraw from the account.

To run this command from the root directory: 

```php bank.php account:open [--balance --overdraft]```

### Options

__--balance__ - Display the balance of the account.

__--overdraft__ - Display the overdraft limit of the account.

### Unit Tests

To run this command from the root directory:

```./vendor/bin/phpunit```