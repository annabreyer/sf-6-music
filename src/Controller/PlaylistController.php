<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Playlist;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlaylistController extends AbstractController
{
    #[Route('/playlist/{id}', name: 'playlist_show')]
    public function index(Playlist $playlist): Response
    {

        return $this->render('playlist/index.html.twig', [
            'playlist' => $playlist,]);
    }
}
