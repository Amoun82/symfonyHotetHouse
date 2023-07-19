<?php

namespace App\Controller;


use App\Repository\SliderRepository;
use App\Repository\ChambreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SliderRepository $slider, ChambreRepository $chambre): Response
    {
        $sliders = $slider->findAll();
        $chambres = $chambre->findAll();
        return $this->render('home/index.html.twig', [
            'sliders' => $sliders,
            'chambres' => $chambres,
        ]);
    }

}
