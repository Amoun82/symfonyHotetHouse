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
    public function index(AvisRepository $ar, Request $request, EntityManagerInterface $manager, Avis $avis = null): Response
    {
        //dd($request->request->get('categorie'));

        $choixAvis = $request->request->get('categorie');

        $avisGet = null ;

        switch ($choixAvis) {
            case 'chambre':
                $avisGet = $ar->findBy(['categorie' => 'chambre']);
                break;
            case 'restaurant':
                $avisGet = $ar->findBy(['categorie' => 'restaurant']);
                break;
            case 'spa':
                $avisGet = $ar->findBy(['categorie' => 'spa']);
                break;
            
            default:
                $avisGet = $ar->findAll();
                break;
        }
        
        // dd($ar->findAll()) ;
        // dd($ar->findBy(['categorie' => 'chambre'])) ;
        
        if($avis == null)
        {
            $avis = new Avis;
        }

        $form = $this->createForm(AvisType::class, $avis);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($request) ;
            $avis->setDateEnregistrement(new \DateTime);
            $manager->persist($avis);
            $manager->flush();

            $this->addFlash('success', 'Vous avez ajouté un avis');

            return $this->redirectToRoute('app_avis');
        }

        return $this->render('avis/index.html.twig', [
            'form' => $form,
            'avis' => $avisGet,
        ]);
    }

}
