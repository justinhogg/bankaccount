# bankaccount
A simple command line interface bank account

### Installation

git clone this repository.

Download composer: curl -s https://getcomposer.org/installer | php

Install dependencies: php composer.phar install

### Commands

*account*

__account:open__ - Open a new bank account.

To run these commands: __php bank.php account:open [--balance --overdraft]__ from the root directory.

### Options

__--balance__ - Return the balance of the account.
__--overdraft__ - Returns the overdraft limit of the account.

### Unit Tests

run __./vendor/bin/phpunit__ from the root directory