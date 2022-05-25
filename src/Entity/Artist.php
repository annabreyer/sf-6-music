<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\ArtistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArtistRepository::class)]
class Artist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    /**
     * @var Collection<int, Album>&iterable<Album>
     */
    #[ORM\OneToMany(targetEntity: Album::class, mappedBy: 'Artist', cascade: ['persist'])]
    private Collection $albums;

    /**
     * @var Collection<int, Song>&iterable<Song>
     */
    #[ORM\OneToMany(targetEntity: Song::class, mappedBy: 'Artist', cascade: ['persist'])]
    private Collection $songs;

    public function __construct(string $name)
    {
        $this->name   = $name;
        $this->albums = new ArrayCollection();
        $this->songs  = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return Album[]|Collection<int, Album>
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    /**
     * @param Album[]|Collection<int, Album> $albums
     */
    public function setAlbums(Collection $albums): void
    {
        $this->albums = $albums;
    }

    public function addAlbum(Album $album): self
    {
        if (null === $album->getArtist()) {
            $album->setArtist($this);
        }

        if ($this->albums->contains($album)) {
            return $this;
        }

        $this->albums->add($album);

        return $this;
    }

    public function removeAlbum(Album $album): self
    {
        $this->albums->removeElement($album);

        return $this;
    }

    /**
     * @return Collection<int, Song>|Song[]
     */
    public function getSongs(): Collection
    {
        return $this->songs;
    }

    /**
     * @param Collection<int, Song>|Song[] $songs
     */
    public function setSongs(Collection $songs): void
    {
        $this->songs = $songs;
    }

    public function addSong(Song $song): self
    {
        if (null === $song->getArtist()) {
            $song->setArtist($this);
        }

        if ($this->songs->contains($song)) {
            return $this;
        }

        $this->songs->add($song);

        return $this;
    }

    public function removeSong(Song $song): self
    {
        $this->songs->removeElement($song);

        return $this;
    }
}
