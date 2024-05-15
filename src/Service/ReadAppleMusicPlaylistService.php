<?php

declare(strict_types = 1);

namespace App\Service;

use App\Entity\Playlist;
use App\Manager\SongManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;

class ReadAppleMusicPlaylistService
{
    private const KEY_TITLE  = 0;
    private const KEY_ARTIST = 1;
    private const KEY_ALBUM = 3;

    public function __construct(private EntityManagerInterface $entityManager, private SongManager $songManager)
    {

    }

    public function read(File $playlisteFile, Playlist $playlist): void
    {
        $content = mb_convert_encoding($playlisteFile->getContent(), "UTF-8", "UTF-16LE");

        $lines = explode("\r", $content);
        unset($lines[0]);

        foreach ($lines as $line) {
            $lineContents = explode("\t", $line);

            if (false === isset($lineContents[self::KEY_ALBUM])) {
                continue;
            }

            $song = $this->songManager->createSong(
                $lineContents[self::KEY_TITLE],
                $lineContents[self::KEY_ARTIST],
                $lineContents[self::KEY_ALBUM]
            );

            $playlist->addSong($song);

            $this->entityManager->flush();
        }
    }
}
