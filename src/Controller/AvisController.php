<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AvisController extends AbstractController
{
    #[Route('/avis', name: 'app_avis')]
    public function index(AvisRepository $ar, Request $request, EntityManagerInterface $manager): Response
    {
        $ar->findAll();

        $avis = new Avis;

        $form = $this->createForm(AvisType::class, $avis);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setDateEnregistrement(new \DateTime);
            $manager->persist($avis);
            $manager->flush();

            $this->addFlash('success', 'Vous avez ajouté un avis');

            return $this->redirectToRoute('app_avis');
        }

        return $this->render('avis/index.html.twig', [
            'form' => $form,
            'avis' => $ar->findAll(),
        ]);
    }

}
