<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use App\Repository\DragonTreasureRepository;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use function Symfony\Component\String\u;

#[ORM\Entity(repositoryClass: DragonTreasureRepository::class)]
#[ApiResource(
    shortName: 'Treasure', # Seta um nome curto para o endpoint
    description: 'A rare and valuable treasure.', # Seta uma descrição para o recurso
    operations: [ # Seta as operações permitidas para o recurso
        new Get(), # Seta um template de URI para a operação com um parâmetro dinâmico
        new GetCollection(), # Seta um template de URI para a operação
        new Post(),
        new Put(),
        new Patch(),
        new Delete(),
    ],
    formats: [
        'jsonld',
        'json',
        'html',
        'jsonhal',
        'csv' => 'text/csv', # Seta csv como um formato de resposta
    ],
    normalizationContext: [
        'groups' => ['treasure:read'] # Seta o grupo de serialização para leitura
    ],
    denormalizationContext: [
        'groups' => ['treasure:write'] # Seta o grupo de serialização para escrita
    ], # Seta a quantidade de itens por página
    paginationItemsPerPage: 10,
)] # Expõe a entidade como um recurso da API
#[ApiFilter(PropertyFilter::class)] # Habilita o filtro de propriedades que permite selecionar quais campos serão retornados
class DragonTreasure
{
    public function __construct()
    {
        $this->plunderedAt = new \DateTimeImmutable();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['treasure:read', 'treasure:write'])] # Seta o grupo de serialização para leitura
    #[ApiFilter(SearchFilter::class, strategy: 'partial')] # Seta um filtro de pesquisa para o campo
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50, maxMessage: 'Describe your loot in 50 characters or less')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups('treasure:read')] # Seta o grupo de serialização para leitura
    #[ApiFilter(SearchFilter::class, strategy: 'partial')] # Seta um filtro de pesquisa para o campo
    #[Assert\NotBlank]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['treasure:read', 'treasure:write'])] # Seta o grupo de serialização para leitura
    #[ApiFilter(RangeFilter::class)] # Seta um filtro de intervalo para o campo
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $value = null;

    #[ORM\Column]
    #[Groups(['treasure:read', 'treasure:write'])] # Seta o grupo de serialização para leitura
    #[Assert\LessThanOrEqual(10)]
    private ?int $coolFactor = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $plunderedAt;

    #[ORM\Column]
    #[ApiFilter(BooleanFilter::class)]
    private bool $isPublished = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    #[Groups('treasure:read')] # Seta o grupo de serialização para leitura
    public function getShortDescription(): ?string
    {
        // Trunca a descrição para 40 caracteres
        return u($this->description)->truncate(40, '...');
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getCoolFactor(): ?int
    {
        return $this->coolFactor;
    }

    public function setCoolFactor(int $coolFactor): static
    {
        $this->coolFactor = $coolFactor;

        return $this;
    }

    public function getPlunderedAt(): ?\DateTimeImmutable
    {
        return $this->plunderedAt;
    }

    /**
     * A human-readable representation of the time since the treasure was plundered.
     */
    #[Groups('treasure:read')] # Seta o grupo de serialização para leitura
    public function getPlunderedAtAgo(): string
    {
        return Carbon::instance($this->plunderedAt)->diffForHumans();
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    #[Groups('treasure:write')] # Seta o grupo de serialização para escrita
    #[SerializedName('description')] # Seta um nome alternativo para o campo
    public function setTextDescription(string $description): self
    {
        $this->description = nl2br($description);

        return $this;
    }

    public function setPlunderedAt(\DateTimeImmutable $plunderedAt): self
    {
        $this->plunderedAt = $plunderedAt;

        return $this;
    }
}
