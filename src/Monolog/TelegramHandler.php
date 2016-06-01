<?php
namespace VladimirYuldashev\Monolog;

use Monolog\Handler\AbstractHandler;
use Telegram\Bot\Api;

class TelegramHandler extends AbstractHandler
{

    private $token;
    private $chatId;
    private $async;

    public function __construct($level, $token, $chatId, $async = false)
    {
        $this->token = $token;
        $this->chatId = $chatId;
        $this->async = $async;

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
     * @return Boolean true means that this handler handled the record, and that bubbling is not permitted.
     *                        false means the record was either not processed or that this handler allows bubbling.
     */
    public function handle(array $record)
    {
        $text = json_encode($record, JSON_UNESCAPED_UNICODE);

        $this->telegram()->sendMessage([
            'chat_id' => $this->chatId,
            'text' => $text,
        ]);
    }

    private function telegram()
    {
        return new Api($this->token, $this->async);
    }

}