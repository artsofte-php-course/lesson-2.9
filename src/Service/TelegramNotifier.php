<?php

namespace App\Service;

class TelegramNotifier {

    protected $apiKey;

    protected $chatId;


    protected $httpClient;

    public function __construct(string $apiKey, int $chatId)
    {
        $this->apiKey = $apiKey;
        $this->chatId = $chatId;

        $this->httpClient = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.telegram.org'
        ]);
    }

    protected function uri(string $uri)
    {
        return sprintf('/bot%s%s', $this->apiKey, $uri);
    }

    public function notify(string $message)
    {
        $this->httpClient->get($this->uri('/sendMessage'), [
            'query' => [
                'chat_id' => $this->chatId,
                'text' => $message
            ]
        ]);
    }

}