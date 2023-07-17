<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChambreController extends AbstractController
{
    #[Route('/admin/chambre', name: 'app_admin_chambre')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }
}
