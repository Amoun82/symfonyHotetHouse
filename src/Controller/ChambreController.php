<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChambreController extends AbstractController
{
    #[Route('/chambres', name: 'app_chambre')]
    public function index(ChambreRepository $chambre): Response
    {
        return $this->render('chambre/index.html.twig', [
            'chambres' => $chambre->findAll(),
        ]);
    }

    #[Route('/chambre/detail/{id}', name: 'app_home_bedroom_show')]
    public function detailChambre($id, ChambreRepository $chambre, Request $request, EntityManagerInterface $manager): Response
    {
        $chambre = $chambre->find($id);
        $commande = new Commande;

        $form = $this->createForm(CommandeType::class, $commande, ['is_chambre' => true]);

        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($chambre->getCommandes());
            foreach ($chambre->getCommandes()->getValues() as $chambreDate) {
                if($commande->getDateArrivee() < $chambreDate->getDateDepart() && $commande->getDateDepart() > $chambreDate->getDateArrivee() )
                {
                    $this->addFlash('danger', 'la chambre est déjà réservé');

                    return $this->redirectToRoute('app_home_bedroom_show', ['id' => $id]);
                }
            }
            $interval = $commande->getDateArrivee()->diff($commande->getDateDepart()) ;
            $prix = $chambre->getPrixJournalier() * ($interval->days + 1);
            $commande->setIdChambre($chambre) ;
            $commande->setDateEnregistrement(new \DateTime);
            $commande->setPrixTotal($prix) ;
            $manager->persist($commande);
            $manager->flush();
            $this->addFlash('success', 'une commande a été ajouté');

            return $this->redirectToRoute('app_home');
        }


        return $this->render('chambre/formChambre.html.twig', [
            'chambre' => $chambre,
            'form' => $form,
        ]);
    }
}
