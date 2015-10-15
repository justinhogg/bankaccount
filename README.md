# bankaccount
A simple command line interface bank account

## Installation

git clone this repository.
Download composer: curl -s https://getcomposer.org/installer | php
Install dependencies: php composer.phar install

## Commands

*account*
  __account:open__ - Open a new bank account.
To run these commands: php bank.php account:open

## Options

__--balance__ - returns the balance of the account with any command

## Unit Tests

run ./vendor/bin/phpunit from the root directory