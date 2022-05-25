<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: Artist::class, inversedBy: 'Albums', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'artist_id', referencedColumnName: 'id')]
    private Artist|null $artist;

    /**
     * @var Collection<int, Song>&iterable<Song>
     */
    #[ORM\OneToMany(targetEntity: Song::class, mappedBy: 'Album', cascade: ['persist'])]
    private Collection $songs;

    public function __construct(string $name)
    {
        $this->name  = $name;
        $this->songs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getArtist(): Artist
    {
        return $this->artist;
    }

    public function setArtist(Artist $artist): void
    {
        $this->artist = $artist;
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
        if (null === $song->getAlbum()) {
            $song->setAlbum($this);
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
