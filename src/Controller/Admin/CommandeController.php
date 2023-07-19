<?php

namespace App\Controller\Admin;

use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_admin_commande')]
    public function index(): Response
    {
        return $this->render('admin/commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }

    #[Route('/commande/gestion', name: 'app_admin_commande_gestion')]
    public function gestionVehicule(CommandeRepository $commande): Response
    {
        $commandes = $commande->findAll();
        return $this->render('admin/commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }
}
