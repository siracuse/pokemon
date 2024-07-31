<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokemonAPI
{

    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function fetchDataByUrl($url)
    {
        $response = $this->httpClient->request('GET', $url);
        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Error fetching data from PokeAPI');
        }
        return $response->toArray();
    }


    public function getSimplifiedDataPokemon($data)
    {
        foreach($data['results'] as $pokemon) {
            $pokemonAllInfo = $this->fetchDataByUrl($pokemon['url']);
            $types = [];
            foreach($pokemonAllInfo['types'] as $type) {
                $types[] = $type['type']['name'];
            }

            $pokemons[] = [
                'name' => $pokemonAllInfo['name'],
                'id' => $pokemonAllInfo['id'],
                'sprites' => $pokemonAllInfo['sprites']['front_default'],
                'types' => $types
            ];
            unset($pokemonAllInfo);
        }
        return $pokemons;
    }

//    TODO reécrire la fonction
    public function getAllTypes(array $pokemons): array
    {
        $types = [];
        foreach ($pokemons as $pokemon) {
            foreach ($pokemon['types'] as $type) {
                if (!in_array($type, $types)) {
                    $types[] = $type;
                }
            }
        }
        return $types;
    }


}