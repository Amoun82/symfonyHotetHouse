<?php

namespace App\Controller\Admin;

use App\Entity\Avis;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class AvisController extends AbstractController
{
    #[Route('/avis', name: 'app_admin_avis_gestion')]
    public function index(AvisRepository $ar): Response
    {
        return $this->render('admin/avis/index.html.twig', [
            'avis' => $ar->findAll(),
        ]);
    }

    #[Route('/avis/supprimer/{id}', name: 'app_admin_avis_delete')]
    public function delete(EntityManagerInterface $manager, Avis $avis): Response
    {
        $manager->remove($avis);
        $manager->flush();
        $this->addFlash('danger', 'un avis a été supprimé');

        return $this->redirectToRoute('app_admin_avis_gestion', [], Response::HTTP_SEE_OTHER);
    }
}
