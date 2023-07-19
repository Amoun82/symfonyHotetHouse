<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index(): Response
    {
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }

    #[Route('/commande/ajouter', name: 'app_commande_new')]
    public function formCommande(Request $request, EntityManagerInterface $manager)
    {

        $commande = new Commande;

        $form = $this->createForm(CommandeType::class, $commande);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $interval = $commande->getDateArrivee()->diff($commande->getDateDepart()) ;
            $prix = $commande->getIdChambre()->getPrixJournalier() * ($interval->days + 1);
            $commande->setDateEnregistrement(new \DateTime);
            $commande->setPrixTotal($prix) ;
            $manager->persist($commande);
            $manager->flush();

            $this->addFlash('success', 'une commande a été ajouté');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('commande/formCommande.html.twig', [
            'form' => $form,
        ]);
    }
}
