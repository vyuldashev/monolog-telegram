<?php

namespace VladimirYuldashev\Monolog;

use Monolog\Handler\AbstractHandler;
use Telegram\Bot\Api;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;

class TelegramHandler extends AbstractHandler
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
     * Handles a record.
     *
     * All records may be passed to this method, and the handler should discard
     * those that it does not want to handle.
     *
     * The return value of this function controls the bubbling process of the handler stack.
     * Unless the bubbling is interrupted (by returning true), the Logger class will keep on
     * calling further handlers in the stack with a given log record.
     *
     * @param  array $record The record to handle
     *
     * @return Boolean true means that this handler handled the record, and that bubbling is not permitted.
     *                        false means the record was either not processed or that this handler allows bubbling.
     */
    public function handle(array $record)
    {
        $text = json_encode($record, JSON_UNESCAPED_UNICODE);

        try {
            $this->telegram()->sendMessage($this->chatId, $text);

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    private function telegram(): BotApi
    {
        return new BotApi($this->token);
    }
}