<?php

declare(strict_types=1);

namespace VladimirYuldashev\Monolog\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use VladimirYuldashev\Monolog\TelegramHandler;

class TelegramHandlerTest extends TestCase
{
    public function testSuccess(): void
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

    public function testCatchesException(): void
    {
        $mock = new MockHandler([
            new RequestException('Error Communicating with Server', new Request('GET', 'telegram'))
        ]);
        $client = new Client(['handler' => $mock]);

        $handler = new TelegramHandler(Logger::DEBUG, 'foo', 1, true, $client);

        $logger = new Logger('handler', [$handler]);

        $result = $logger->debug('test', ['foo' => 'bar']);

        $this->assertTrue($result);
    }
}