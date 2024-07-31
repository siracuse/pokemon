<?php

namespace App\Controller;

use App\Form\RechercheType;
use App\Service\PokemonAPI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{


    #[Route('/', name: 'index', methods: 'GET')]
    public function index(Request $request, PokemonAPI $pokemonAPI)
    {
        $data = $pokemonAPI->fetchDataByUrl('https://pokeapi.co/api/v2/pokemon?limit=60');
        $pokemons = $pokemonAPI->getSimplifiedDataPokemon($data);
        $types = $pokemonAPI->getAllTypes($pokemons);

        $form = $this->createForm(RechercheType::class, null, [
            'types' => $types,
        ]);

        return $this->render('default/index.html.twig', [
            'pokemons' => $pokemons,
            'form' => $form
        ]);
    }
}
