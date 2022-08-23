<?php


namespace App\Services\Apis;


use GuzzleHttp\Client;

class BaseAPi
{
    protected $client;


    public function __construct()
    {
        $this->client = new Client();
    }
}
