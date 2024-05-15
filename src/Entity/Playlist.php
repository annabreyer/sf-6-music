<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaylistRepository::class)]
class Playlist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    /**
     * @var Collection<int, Song>&iterable<Song>
     */
    #[ORM\ManyToMany(targetEntity: Song::class, inversedBy: 'playlists')]
    #[ORM\JoinTable(name: 'playlist_songs')]
    private Collection $songs;

    /**
     * @var Collection<int, PlaylistType>&iterable<PlaylistType>
     */
    #[ORM\ManyToMany(targetEntity: PlaylistType::class, inversedBy: 'playlists')]
    #[ORM\JoinTable(name: 'playlist_types')]
    private Collection $types;

    public function __construct()
    {
        $this->songs = new ArrayCollection();
        $this->types = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    /**
     * @return Collection<int, PlaylistType>|PlaylistType[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    /**
     * @param Collection<int, PlaylistType>|PlaylistType[] $types
     */
    public function setTypes(Collection $types): void
    {
        $this->types = $types;
    }

    public function addType(PlaylistType $type): self
    {
        if ($this->types->contains($type)) {
            return $this;
        }

        $this->types->add($type);

        return $this;
    }

    public function removeType(PlaylistType $type): self
    {
        $this->types->removeElement($type);

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
