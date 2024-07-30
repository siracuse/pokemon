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
    public function index(HttpClientInterface $httpClient)
    {
        $url = 'https://pokeapi.co/api/v2/pokemon?limit=5';
        $data = $this->fetchData($url);
        $pokemons = [];

        foreach($data['results'] as $pokemonList) {
            $pokemonAllInfo = $this->fetchData($pokemonList['url']);

            foreach($pokemonAllInfo['types'] as $type) {
                $types[] = $type['type']['name'];
            }

            $pokemons[] = [
                'name' => $pokemonAllInfo['name'],
                'id' => $pokemonAllInfo['id'],
                'sprites' => $pokemonAllInfo['sprites']['front_default'],
                'types' => $types
            ];
        }

        return $this->render('default/index.html.twig', [
            'pokemons' => $pokemons
        ]);
    }
}
