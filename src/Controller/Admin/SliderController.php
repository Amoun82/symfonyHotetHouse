<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SliderController extends AbstractController
{
    #[Route('/admin/slider', name: 'app_admin_slider')]
    public function index(): Response
    {
        return $this->render('admin/slider/index.html.twig', [
            'controller_name' => 'SliderController',
        ]);
    }
}
