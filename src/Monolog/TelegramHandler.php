<?php

declare(strict_types=1);

namespace VladimirYuldashev\Monolog;

use Monolog\Handler\AbstractProcessingHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;

class TelegramHandler extends AbstractProcessingHandler
{
    private $token;
    private $chatId;

    public function __construct(int $level, string $token, int $chatId)
    {
        $this->token = $token;
        $this->chatId = $chatId;

        parent::__construct($level, false);
    }

    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param  array $record
     *
     * @return void
     */
    protected function write(array $record): void
    {
        try {
            $message = substr($record['formatted'], 0, 4096);

            $this->telegram()->sendMessage($this->chatId, $message);
        } catch (Exception $exception) {
        }
    }


    private function telegram(): BotApi
    {
        return new BotApi($this->token);
    }
}