<?php declare(strict_types=1);

namespace App\Models;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GiphyApiClient
{
    private Client $client;
    private string $apiKey;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.giphy.com/v1/',
        ]);
        $this->apiKey = $_ENV["API_KEY"];
    }

    public function fetchSearch(string $tag, int $count): ?array
    {
        try {
            $response = $this->client->get('gifs/search', [
                'query' => [
                    'api_key' => $this->apiKey,
                    'q' => $tag,
                    'limit' => $count,
                ],
            ]);
            return $this->getCollection(json_decode($response->getBody()->getContents())->data);
        } catch (GuzzleException $e) {
        }
        return null;
    }

    public function fetchTrending(int $count): ?array
    {
        try {
            $response = $this->client->get('gifs/trending', [
                'query' => [
                    'api_key' => $this->apiKey,
                    'limit' => $count,
                ],
            ]);
            return $this->getCollection(json_decode($response->getBody()->getContents())->data);
        } catch (GuzzleException $e) {
        }
        return null;
    }

    private function getCollection($fetchResults): ?array
    {
        if ($fetchResults != null) {
            $gifs = [];
            foreach ($fetchResults as $giphy) {
                $gifs[] = new GiphyGif("{$giphy->title}", "{$giphy->images->original->url}");
            }
            return $gifs;
        }
        return null;
    }
}
