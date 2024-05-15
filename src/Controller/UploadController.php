<?php declare(strict_types = 1);

namespace App\Controller;

use App\Form\PlaylistUploadType;
use App\Manager\PlaylistManager;
use App\Service\ReadAppleMusicPlaylistService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UploadController extends AbstractController
{

    #[Route('/upload', name: 'upload')]
    public function upload(Request $request, PlaylistManager $playlistManager, ReadAppleMusicPlaylistService $readAppleMusicPlaylistService): Response
    {
        $form = $this->createForm(PlaylistUploadType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $playList = $playlistManager->createPlaylist($data['name'], $data['type']);
            $file = $data['file'];

            $readAppleMusicPlaylistService->read($file, $playList);

            return $this->redirectToRoute('playlist_show', ['id' => $playList->getId()]);
        }

        return $this->render('upload/index.html.twig',[
            'form' => $form->createView(),
        ]);
    }

}