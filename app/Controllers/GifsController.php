<?php declare(strict_types=1);

namespace App\Controllers;

use App\GiphyApiClient;
use App\Core\TwigView;

class GifsController
{
    private GiphyApiClient $giphyApiClient;

    public function __construct()
    {
        $this->giphyApiClient = new GiphyApiClient();
    }

    public function index(): TwigView
    {
        $query = $_GET['q'] ?? '';
        if (!empty($query)) {
            $gifs = $this->giphyApiClient->fetchSearch($query, 20);
        } else {
            $gifs = $this->giphyApiClient->fetchTrending(20);
        }

        return new TwigView("index", ['query' => $query, 'gifs' => $gifs]);
    }
}
