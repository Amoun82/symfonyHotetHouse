<?php

namespace App\Controller\Admin;

use App\Entity\Chambre;
use App\Form\ChambreType;
use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[route('/admin')]
class ChambreController extends AbstractController
{
    #[Route('/chambre', name: 'app_admin_chambre')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/chambre/gestion', name: 'app_admin_chambre_gestion')]
    public function gestionVehicule(ChambreRepository $chambre): Response
    {
        $chambres = $chambre->findAll();
        return $this->render('admin/chambre/index.html.twig', [
            'chambres' => $chambres,
        ]);
    }

    #[Route('/chambre/modifier/{id}', name: ('app_admin_chambre_update'))]
    #[Route('/chambre/ajouter', name: ('app_admin_chambre_new'))]
    public function formChambre(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger, Chambre $chambre = null): Response
    {
        $test = false ;
        if ($chambre == null) {
            $chambre = new Chambre;
            $chambre->setDateEnregistrement(new \DateTime);
            $test = true ;
        }

        $form = $this->createForm(ChambreType::class, $chambre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingFilename = $chambre->getPhoto();

            if ($form->get('photo')->getData()) {
                $imgFile = $form->get('photo')->getData();
                $originalFilename = pathinfo($imgFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imgFile->guessExtension();
                try {
                    $imgFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );

                    if ($existingFilename && file_exists($this->getParameter('uploads_directory') . '/' . $existingFilename)) {
                        unlink($this->getParameter('uploads_directory') . '/' . $existingFilename);
                    }
                } catch (FileException $e) {
                }
                $chambre->setPhoto($newFilename);
            }

            if($test == false)
            {
                $this->addFlash('success', 'la chambre a été modifiée');
            }else {
                $this->addFlash('success', 'une chambre a été ajoutée');
            }
            $manager->persist($chambre);
            $manager->flush();

            return $this->redirectToRoute('app_admin_chambre_gestion');
        }
        return $this->render('admin/Chambre/formChambre.html.twig', [
            'form' => $form,
            'editMode' => $chambre->getId() != null
        ]);
    }

    #[Route('/chambre/supprimer/{id}', name: 'app_admin_chambre_delete')]
    public function delete(EntityManagerInterface $manager, Chambre $chambre): Response
    {
        $manager->remove($chambre);
        $manager->flush();
        $this->addFlash('danger', 'une chambre a été supprimé');

        return $this->redirectToRoute('app_admin_chambre_gestion', [], Response::HTTP_SEE_OTHER);
    }
}
