<?php

namespace App\Entity;

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

    #[ORM\ManyToOne(targetEntity: Artist::class, inversedBy: 'Albums')]
    #[ORM\JoinColumn(name: "artist_id", referencedColumnName: "id")]
    private Artist $artist;

    #[ORM\ManyToOne(targetEntity: Album::class, inversedBy: 'Albums')]
    #[ORM\JoinColumn(name: "album_id", referencedColumnName: "id")]
    private Album $album;

    #[ORM\ManyToMany(targetEntity: Playlist::class, mappedBy: 'Songs', cascade: ["persist"])]
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

    public function setArtist(Artist $artist): void
    {
        $this->artist = $artist;
    }

    public function getAlbum(): Album
    {
        return $this->album;
    }

    public function setAlbum(Album $album): void
    {
        $this->album = $album;
    }

    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function setPlaylists(Collection $playlists): void
    {
        $this->playlists = $playlists;
    }
}
