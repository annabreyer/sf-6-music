<?php

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
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToMany(targetEntity: Song::class, inversedBy: 'Playlists')]
    #[ORM\JoinTable(name: "playlist_songs")]
    private Collection $songs;

    #[ORM\ManyToMany(targetEntity: PlaylistType::class, inversedBy: 'Playlists')]
    #[ORM\JoinTable(name: "playlist_types")]
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
     * @return Collection|Song[]
     */
    public function getSongs(): Collection
    {
        return $this->songs;
    }

    /**
     * @param Collection|Song[] $songs
     */
    public function setSongs(Collection $songs): void
    {
        $this->songs = $songs;
    }

    public function addSong(Song $song): self
    {
        if ($this->songs->contains($song)){
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
     * @return Collection|PlaylistType[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    /**
     * @param Collection|PlaylistType[] $types
     */
    public function setTypes(Collection $types): void
    {
        $this->songs = $types;
    }

    public function addType(PlaylistType $types): self
    {
        if ($this->types->contains($types)){
            return $this;
        }

        $this->types->add($types);

        return $this;
    }

    public function removeType(PlaylistType $types): self
    {
        $this->types->removeElement($types);

        return $this;
    }
}
