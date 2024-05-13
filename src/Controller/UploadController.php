<?php declare(strict_types = 1);

namespace App\Controller;

use App\Form\PlaylistUploadType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UploadController extends AbstractController
{

    #[Route('/upload', name: 'upload')]
    public function upload(Request $request): Response
    {
        $form = $this->createForm(PlaylistUploadType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $file = $data['file'];



            return $this->redirectToRoute('upload');
        }



        return $this->render('upload/index.html.twig',[
            'form' => $form->createView(),
        ]);
    }

}