<?php declare(strict_types=1);

namespace App\Controllers;

use App\Models\GiphyApiClient;
use Twig\Environment;

class ViewsController
{
    private GiphyApiClient $apiClient;
    private Environment $twig;

    public function __construct(GiphyApiClient $apiClient, Environment $twig)
    {
        $this->apiClient = $apiClient;
        $this->twig = $twig;
    }

    public function index()
    {
        $gifs = $this->apiClient->fetchTrending(10);
        echo $this->twig->render('index.twig', ['gifs' => $gifs]);
    }

    public function trending()
    {
        $gifs = $this->apiClient->fetchTrending(30);
        echo $this->twig->render('trending.twig', ['gifs' => $gifs]);
    }

    public function search()
    {
        $tag = $_GET['q'];
        $gifs = $this->apiClient->fetchSearch($tag, 30);
        echo $this->twig->render('search.twig', ['query' => $tag, 'gifs' => $gifs]);
    }
}
