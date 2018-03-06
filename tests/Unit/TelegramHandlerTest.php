<?php

declare(strict_types=1);

namespace VladimirYuldashev\Monolog\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use VladimirYuldashev\Monolog\TelegramHandler;

class TelegramHandlerTest extends TestCase
{
    public function test()
    {
        $mock = new MockHandler([
            new Response()
        ]);
        $client = new Client(['handler' => $mock]);

        $handler = new TelegramHandler(Logger::DEBUG, 'foo', 1, true, $client);

        $logger = new Logger('handler', [$handler]);

        $result = $logger->debug('test', ['foo' => 'bar']);

        $this->assertTrue($result);
    }
}