# Telegram handler for Monolog

## Installation

``` bash
$ composer require vladimir-yuldashev/monolog-telegram
```


## Usage

```php
<?php

use Monolog\Logger;
use VladimirYuldashev\Monolog\TelegramHandler;

$telegramToken = '';
$chatId = 1;

$handler = new TelegramHandler(
    Logger::ERROR,
    $telegramToken,
    $chatId
);

$log = new Logger('name');
$log->pushHandler($handler);

// add records to the log
$log->warning('Foo');
$log->error('Bar');

```