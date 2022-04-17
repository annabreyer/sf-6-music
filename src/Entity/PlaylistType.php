<?php

namespace App\Entity;

use App\Repository\PlaylistTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaylistTypeRepository::class)]
class PlaylistType
{
    const TYPE_SUMMER    = 'summer';
    const TYPE_SPRING    = 'spring';
    const TYPE_AUTUMN    = 'autumn';
    const TYPE_WINTER    = 'winter';
    const TYPE_FAVORITES = 'favorites';
    const TYPE_THEME     = 'theme';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\ManyToMany(targetEntity: Playlist::class, mappedBy: 'types', cascade: ["persist"])]
    private Collection $playlists;

    public function __construct()
    {
        $this->playlists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
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
