<?php

namespace App\Controller\Admin;

use App\Entity\Membre;
use App\Form\MembreType;
use App\Repository\MembreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as Hasher;

class MembreController extends AbstractController
{
    #[Route('/admin/membre', name: 'app_admin_membre')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/admin/membre/modifier/{id}', name: "app_admin_membre_update")]
    #[Route('/admin/membre/ajouter', name: 'app_admin_membre_new')]
    public function formMembre(Request $request, EntityManagerInterface $manager, Membre $membre = null, Hasher $hasher)
    {
        if ($membre == null) {
            $membre = new Membre;
            $form = $this->createForm(MembreType::class, $membre);
        } else {
            $form = $this->createForm(MembreType::class, $membre, ['is_edit' => true]);
        }


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$membre->getId()) {
                $mdp = $form->get("password")->getdata();
                if ($mdp) {
                    $password = $hasher->hashPassword($membre, $mdp); // le mot de passe est encodé
                    $membre->setPassword($password);                 // avant d'être affecté à la propirété 'password' de l'objet
                }
            }

            $membre->setDateEnregistrement(new \DateTime);
            $manager->persist($membre);
            $manager->flush();
            return $this->redirectToRoute('app_admin_membre');
        }

        return $this->render('admin/membre/formMembre.html.twig', [
            'form' => $form,
            'editMode' => $membre->getId() != null
        ]);
    }

    #[Route('/admin/membre/gestion', name: 'app_admin_membre_gestion')]
    public function gestionVehicule(MembreRepository $membre): Response
    {
        $membres = $membre->findAll();
        return $this->render('admin/membre/index.html.twig', [
            'membres' => $membres,
        ]);
    }
}
