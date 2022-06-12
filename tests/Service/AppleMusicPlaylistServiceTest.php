<?php declare(strict_types = 1);

namespace App\Tests\Service;

use App\Service\AppleMusicPlaylistService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AppleMusicPlaylistServiceTest extends KernelTestCase
{
    public function testGetInputReturnsArrayWithLines()
    {
        $appleMusicPlaylistService = new AppleMusicPlaylistService();

        $songs = $appleMusicPlaylistService->getInput('tests/data/ApplePlaylist.txt');
        $this->assertCount(8, $songs);

        $empty = $appleMusicPlaylistService->getInput('tests/data/emptyPlaylist.txt');
        $this->assertCount(0, $empty);
    }

    public function testLoadPlaylistReturnsAssociativeArray()
    {
        $appleMusicPlaylistService = new AppleMusicPlaylistService();

        $playlist = $appleMusicPlaylistService->loadPlaylist('tests/data/ApplePlaylist.txt');

        $this->assertEquals(count(AppleMusicPlaylistService::PLAYLIST_HEADERS), count($playlist[0]));
        $this->assertEquals('Nothing\'s Gonna Change My Love For You', $playlist[0]['Name']);
        $this->assertEquals('Music', $playlist[6]['Name']);
        $this->assertEquals('Glenn Medeiros', $playlist[0]['Artist']);
        $this->assertEquals('Bon Jovi', $playlist[1]['Artist']);
        $this->assertEquals('Kuschelrock 3 (Disc 2)', $playlist[0]['Album']);
    }
}
