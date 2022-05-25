<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\PlaylistTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaylistTypeRepository::class)]
class PlaylistType
{
    public const TYPE_SUMMER    = 'summer';
    public const TYPE_SPRING    = 'spring';
    public const TYPE_AUTUMN    = 'autumn';
    public const TYPE_WINTER    = 'winter';
    public const TYPE_FAVORITES = 'favorites';
    public const TYPE_THEME     = 'theme';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\ManyToMany(targetEntity: Playlist::class, mappedBy: 'types', cascade: ['persist'])]
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
