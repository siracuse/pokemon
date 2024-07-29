<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DefaultController extends AbstractController
{

    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    private function fetchData($url)
    {
        $response = $this->httpClient->request('GET', $url);
        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Error fetching data from PokeAPI');
        }
        return $response->toArray();
    }



    #[Route('/', name: 'index', methods: 'GET')]
    public function index(HttpClientInterface $httpClient): JsonResponse
    {

        $url = 'https://pokeapi.co/api/v2/generation/1/';
        $data = $this->fetchData($url);

        return new JsonResponse($data['pokemon_species']);
    }
}
