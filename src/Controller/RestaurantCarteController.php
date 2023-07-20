<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantCarteController extends AbstractController
{
    #[Route('/restaurant/carte', name: 'app_restaurant_carte')]
    public function index(): Response
    {
        return $this->render('restaurant_carte/index.html.twig', [
            'controller_name' => 'RestaurantCarteController',
        ]);
    }
}
