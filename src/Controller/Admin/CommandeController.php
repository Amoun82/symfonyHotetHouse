<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function gestionCommande(CommandeRepository $commande): Response
    {
        $commandes = $commande->findAll();
        return $this->render('admin/commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }


    #[Route('/commande/modifier/{id}', name: "app_admin_commande_update")]
    public function formCommande(Request $request, EntityManagerInterface $manager, Commande $commande)
    {
        
        $form = $this->createForm(CommandeType::class, $commande, ['is_admin' => true]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $interval = $commande->getDateArrivee()->diff($commande->getDateDepart()) ;
            $prix = $commande->getIdChambre()->getPrixJournalier() * ($interval->days + 1);
            $commande->setPrixTotal($prix);
            $manager->persist($commande);
            $manager->flush();

            $this->addFlash('success', 'une commande a été modifié');

            return $this->redirectToRoute('app_admin_commande_gestion');
        }

        return $this->render('admin/commande/formCommande.html.twig', [
            'form' => $form,
            'editMode' => $commande->getId() != null
        ]);
    }
}
