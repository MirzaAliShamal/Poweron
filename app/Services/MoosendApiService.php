<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MoosendApiService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.moosend.api_key');
        $this->baseUrl = config('services.moosend.base_url');
    }

    public function get($endpoint, $params = [])
    {
        $params['apikey'] = $this->apiKey;
        return Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-ApiKey' => $this->apiKey,
        ])->get($this->baseUrl . $endpoint, $params);
    }

    public function post($endpoint, $data = [])
    {
        return Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-ApiKey' => $this->apiKey,
        ])->post($this->baseUrl . $endpoint.'?apikey='.$this->apiKey, $data);
    }

    public function delete($endpoint, $data = [])
    {
        return Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-ApiKey' => $this->apiKey,
        ])->delete($this->baseUrl . $endpoint.'?apikey='.$this->apiKey, $data);
    }

    // Add other methods (put, delete, etc.) as needed
}

