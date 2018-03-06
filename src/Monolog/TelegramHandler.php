<?php

declare(strict_types=1);

namespace VladimirYuldashev\Monolog;

use Exception;
use GrahamCampbell\GuzzleFactory\GuzzleFactory;
use Monolog\Handler\AbstractProcessingHandler;

class TelegramHandler extends AbstractProcessingHandler
{
    private $client;
    private $token;
    private $chatId;

    public function __construct(int $level, string $token, int $chatId, bool $bubble = true)
    {
        $this->client = GuzzleFactory::make(['base_uri' => 'https://api.telegram.org']);
        $this->token = $token;
        $this->chatId = $chatId;

        parent::__construct($level, $bubble);
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
            $message = '[' . gethostname() . '] ' . $record['formatted'];
            $message = substr($message, 0, 4096);

            $this->client->post('/bot' . $this->token . '/sendMessage', [
                'form_params' => [
                    'chat_id' => $this->chatId,
                    'text' => $message,
                ],
            ]);
        } catch (Exception $exception) {

        }
    }
}