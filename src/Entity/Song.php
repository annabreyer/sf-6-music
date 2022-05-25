<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Exceptions\InvalidArtistException;
use App\Repository\SongRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SongRepository::class)]
class Song
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\ManyToOne(targetEntity: Artist::class, inversedBy: 'Albums', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'artist_id', referencedColumnName: 'id')]
    private Artist $artist;

    #[ORM\ManyToOne(targetEntity: Album::class, inversedBy: 'Albums', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'album_id', referencedColumnName: 'id')]
    private Album $album;

    #[ORM\ManyToMany(targetEntity: Playlist::class, mappedBy: 'Songs', cascade: ['persist'])]
    private Collection $playlists;

    public function __construct()
    {
        $this->playlists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getArtist(): Artist
    {
        return $this->artist;
    }

    public function setArtist(Artist $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getAlbum(): Album
    {
        return $this->album;
    }

    public function setAlbum(Album $album): self
    {
        $this->album = $album;

        if (null === $this->getArtist()) {
            $this->artist = $album->getArtist();

            return $this;
        }

        if ($album->getArtist() !== $this->getArtist()) {
            throw new InvalidArtistException('Album artist and song artist do not match.');
        }

        return $this;
    }

    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function setPlaylists(Collection $playlists): self
    {
        $this->playlists = $playlists;

        return $this;
    }
}
