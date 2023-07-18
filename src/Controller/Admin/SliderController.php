<?php

namespace App\Controller\Admin;

use App\Entity\Slider;
use App\Entity\Chambre;
use App\Form\SliderType;
use App\Repository\SliderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin')]
class SliderController extends AbstractController
{
    #[Route('/slider', name: 'app_admin_slider')]
    public function index(): Response
    {
        return $this->render('admin/slider/index.html.twig', [
            'controller_name' => 'SliderController',
        ]);
    }

    #[Route('/Slider/gestion', name: 'app_admin_slider_gestion')]
    public function gestionVehicule(SliderRepository $slider): Response
    {
        $sliders = $slider->findAll();
        return $this->render('admin/slider/index.html.twig', [
            'sliders' => $sliders,
        ]);
    }

    #[Route('/slider/modifier/{id}', name: ('app_admin_slider_update'))]
    #[Route('/slider/ajouter', name: ('app_admin_slider_new'))]
    public function formChambre(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger, Slider $slider = null): Response
    {  
        $test = false ;
        if ($slider == null) {
            $slider = new Slider;
            $slider->setDateEnregistrement(new \DateTime);
            $test = true ;
        }

        $form = $this->createForm(SliderType::class, $slider);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingFilename = $slider->getPhoto();

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
                $slider->setPhoto($newFilename);
            }
            $manager->persist($slider);
            $manager->flush();

            if($test == false)
            {
                $this->addFlash('success', 'un slider a été modifié');
            }else {
                $this->addFlash('success', 'un slider a été ajouté');
            }

            return $this->redirectToRoute('app_admin_slider_gestion');
        }
        return $this->render('admin/slider/formSlider.html.twig', [
            'form' => $form,
            'editMode' => $slider->getId() != null
        ]);
    }

    #[Route('/slider/supprimer/{id}', name: 'app_admin_slider_delete')]
    public function delete(EntityManagerInterface $manager, Slider $slider): Response
    {
        $manager->remove($slider);
        $manager->flush();
        $this->addFlash('danger', 'un slider a été supprimé');

        return $this->redirectToRoute('app_admin_slider_gestion', [], Response::HTTP_SEE_OTHER);
    }
}
