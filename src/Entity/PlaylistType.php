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
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $type;

    /**
     * @var Collection<int, Playlist>&iterable<Playlist>
     */
    #[ORM\ManyToMany(targetEntity: Playlist::class, mappedBy: 'types', cascade: ['persist'])]
    private Collection $playlists;

    public static function getTypes(): array
    {
        return [
            self::TYPE_SUMMER    => self::TYPE_SUMMER,
            self::TYPE_SPRING    => self::TYPE_SPRING,
            self::TYPE_AUTUMN    => self::TYPE_AUTUMN,
            self::TYPE_WINTER    => self::TYPE_WINTER,
            self::TYPE_FAVORITES => self::TYPE_FAVORITES,
            self::TYPE_THEME     => self::TYPE_THEME,
        ];
    }

    public function __construct(string $type)
    {
        $this->playlists = new ArrayCollection();
        $this->type      = $type;
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

    /**
     * @return Collection<int, Playlist>|Playlist[]
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    /**
     * @param Collection<int, Playlist>|Playlist[] $playlists
     */
    public function setPlaylists(Collection $playlists): void
    {
        $this->playlists = $playlists;
    }
}
