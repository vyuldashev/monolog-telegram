<?php

namespace VladimirYuldashev\Monolog;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractHandler;
use Monolog\Logger;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;

class TelegramHandler extends AbstractHandler
{
    private $token;
    private $chatId;

    private $emojis = [
        Logger::DEBUG => '🚧',
        Logger::INFO => '‍🗨',
        Logger::NOTICE => '🕵',
        Logger::WARNING => '⚡️',
        Logger::ERROR => '🚨',
        Logger::CRITICAL => '🤒',
        Logger::ALERT => '👀',
        Logger::EMERGENCY => '🤕',
    ];

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
        $format = new LineFormatter;

        $context = $record['context'] ? $format->stringify($record['context']) : '';
        $date = $record['datetime']->format('Y-m-d H:i:s');

        $message = gethostname().' '.$date.PHP_EOL.$this->emojis[$record['level']].$record['message'].$context;

        try {
            $this->telegram()->sendMessage($this->chatId, $message);

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