<?php declare(strict_types = 1);

namespace App\Controller;

use App\Controller\Admin\PlaylistCrudController;
use App\Form\PlaylistUploadType;
use App\Manager\PlaylistManager;
use App\Service\ReadAppleMusicPlaylistService;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UploadController extends AbstractController
{

    #[Route('/upload', name: 'upload')]
    public function upload(
        Request $request,
        PlaylistManager $playlistManager,
        ReadAppleMusicPlaylistService $readAppleMusicPlaylistService,
        AdminUrlGenerator $adminUrlGenerator

    ): Response
    {
        $form = $this->createForm(PlaylistUploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $file = $data['file'];

            $playList = $playlistManager->createPlaylist($file->getClientOriginalName(), $data['type']);

            $readAppleMusicPlaylistService->read($file, $playList);

            $url = $adminUrlGenerator->setController(PlaylistCrudController::class)->set('id', $playList->getId())->generateUrl();

            return $this->redirect($url);
        }

        return $this->render('upload/index.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}
