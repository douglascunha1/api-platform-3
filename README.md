# API Platform 3

- O que é o API Platform? Basicamente, é um framework PHP que permite a criação de APIs REST de forma rápida e fácil. Ele é baseado em Symfony e Doctrine, e é uma ferramenta poderosa para quem deseja criar APIs RESTful de forma rápida e fácil.
- Para instalar o API Platform, você precisa ter o Composer instalado. Se você não tiver o Composer instalado, você pode baixá-lo em [https://getcomposer.org/](https://getcomposer.org/).
- Para instalar o API Platform, você precisa executar o seguinte comando:
```bash
composer require api
```

- O comando acima é um flex alias para o comando `composer require api-platform/api-pack`.
- Vamos instalar o maker-bundle para criar entidades, controllers, etc.
```bash
composer require maker --dev
```

- Após isso, basta executar `./bin/console make:entity` e seguir as instruções para criar uma entidade. A partir de agora irei utilizar um alias chamado `sc` que está configurando no meu arquivo `bashrc` para executar o comando `./bin/console ou symfony console`.
- O resultado do comando `sc make:entity` é a criação de uma entidade no diretório `src/Entity/`. Vejam o exemplo abaixo:
```php
<?php

namespace App\Entity;

use App\Repository\DragonTreasureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DragonTreasureRepository::class)]
class DragonTreasure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $value = null;

    #[ORM\Column]
    private ?int $coolFactor = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?bool $isPublished = null;

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

    public function setDescription(string $description): static
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
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
}
```

- Ou seja, a entidade `DragonTreasure` foi criada com os campos `id`, `name`, `description`, `value`, `coolFactor`, `createdAt` e `isPublished`. Além disso, foram criados os métodos `get` e `set` para cada campo.
- Em seguida, vamos executar o comando `docker compose up -d` para subir o banco de dados. Por padão, o banco utilizado pela API Platform é o PostgreSQL.
- Após isso, podemos criar nossa migration, para isso execute o comando `sc make:migration` e em seguida `sc doctrine:migrations:migrate`. O comando `sc make:migration` irá criar um arquivo de migração no diretório `src/Migrations/nome_da_migration` e o comando `sc doctrine:migrations:migrate` irá executar a migração.

- Feito tudo isso, vamos editar nossa entidade `DragonTreasure` e adicionar a anotação `#[ApiResource]` logo acima da classe, dessa forma nossa entidade será exposta como um recurso da API.
- Por padrão, o API Platform faz uso do Swagger para documentar a API. Para acessar a documentação da API, basta acessar a URL `http://localhost:8000/api`, mas é possível utilizar `ReDoc` para a documentação da API, basta alterar clicar em `ReDoc` no canto inferior direito da página.
- Para visualizar os dados da API em `JSON`, basta acessar `http://127.0.0.1:8001/api/dragon_treasures.jsonld`. Note que `.jsonld` foi passado como formatado desejado, mas é possível setar outros formatos.
- O arquivo `config/routes/api_platform.yaml` é responsável por configurar o API Platform. Nele é possível configurar diversos aspectos da API, por exemplo o prefixo da URL que por padrão é `/api`.
- Para visualizarmos todas as informações de forma detalhada da especificação OpenAPI, basta acessar `http://127.0.0.1:8001/api/docs.jsonopenapi`.
- JSON-LD é um formato de serialização de dados baseado em JSON e Linked Data. Ele é uma recomendação do W3C e é utilizado para representar dados de forma estruturada e semântica.

- Para debugar nossa API, podemos instalar o pacote usando o comando `composer require debug`. Com isso, teremos uma barra de debug no final da nossa página.
- É possível personalizar nossa API Resource como visto abaixo:
```php
#[ApiResource(
    shortName: 'Treasure', # Seta um nome curto para o endpoint
    description: 'A rare and valuable treasure.', # Seta uma descrição para o recurso
    operations: [ # Seta as operações permitidas para o recurso
        new Get(uriTemplate: '/dragon-plunder/{id}'), # Seta um template de URI para a operação com um parâmetro dinâmico
        new GetCollection(uriTemplate: '/dragon-plunder'), # Seta um template de URI para a operação
        new Post(),
        new Put(),
        new Patch(),
        new Delete(),
    ]
)] # Expõe a entidade como um recurso da API
```

- A documentação acima é autoexplicativa.

- Para serializar os dados da API, o API Platform utiliza o `Serializer Component` do Symfony. Ele é responsável por serializar e deserializar objetos PHP em diferentes formatos, como JSON, XML, CSV, etc.
- Podemos alterar nossa entidade para adicionar métodos novos, por exemplo, abaixo temos um construtor que já adiciona automaticamente a data de criação do recurso:
```php
<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\DragonTreasureRepository;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity(repositoryClass: DragonTreasureRepository::class)]
#[ApiResource(
    shortName: 'Treasure', # Seta um nome curto para o endpoint
    description: 'A rare and valuable treasure.', # Seta uma descrição para o recurso
    operations: [ # Seta as operações permitidas para o recurso
        new Get(uriTemplate: '/dragon-plunder/{id}'), # Seta um template de URI para a operação com um parâmetro dinâmico
        new GetCollection(uriTemplate: '/dragon-plunder'), # Seta um template de URI para a operação
        new Post(),
        new Put(),
        new Patch(),
        new Delete(),
    ]
)] # Expõe a entidade como um recurso da API
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
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $value = null;

    #[ORM\Column]
    private ?int $coolFactor = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $plunderedAt;

    #[ORM\Column]
    private ?bool $isPublished = null;

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

    public function setDescription(string $description): static
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

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }
}
```

- Note que como a data é criada automaticamente no construtor, não é necessário ter métodos como o set.
- Para deixar nossas datas mais legíveis, podemos utilizar um pacote bem legal, para isso instale-o usando o comando `composer require nesbot/carbon`.
- Abaixo temos um exemplo de como utilizar o pacote, note que criamos um método novo que retorna uma string em um formato mais legível:
```php
/**
 * A human-readable representation of the time since the treasure was plundered.
 */
public function getPlunderedAtAgo(): string
{
    return Carbon::instance($this->plunderedAt)->diffForHumans();
}
```

- O resultado retornado pela API é dado por:
```json
{
  "@context": "/api/contexts/Treasure",
  "@id": "/api/dragon-plunder/1",
  "@type": "Treasure",
  "id": 1,
  "name": "Gold coins",
  "description": "Taken from Scrooge McDuck",
  "value": 1990,
  "coolFactor": 9,
  "plunderedAt": "2025-03-12T13:09:51+00:00",
  "isPublished": true,
  "plunderedAtAgo": "2 hours ago"
}
```

- Note que plunderedAtAgo retorna uma string legível.

- Agora vamos falar sobre Serialization Group que é uma forma de controlar quais propriedades de um objeto são serializadas e deserializadas.
- Podemos utilizar normalizationContext e denormalizationContext para controlar a serialização e deserialização de um objeto.
- Por exemplo, normalization é o processo de transformar um objeto PHP em um array ou JSON, enquanto denormalization é o processo de transformar um array ou JSON em um objeto PHP.
- Abaixo temos um exemplo de como utilizar o Serialization Group:
```php
<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\DragonTreasureRepository;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DragonTreasureRepository::class)]
#[ApiResource(
    shortName: 'treasure', # Seta um nome curto para o endpoint
    description: 'A rare and valuable treasure.', # Seta uma descrição para o recurso
    operations: [ # Seta as operações permitidas para o recurso
        new Get(uriTemplate: '/dragon-plunder/{id}'), # Seta um template de URI para a operação com um parâmetro dinâmico
        new GetCollection(uriTemplate: '/dragon-plunder'), # Seta um template de URI para a operação
        new Post(),
        new Put(),
        new Patch(),
        new Delete(),
    ],
    normalizationContext: [
        'groups' => ['treasure:read'] # Seta o grupo de serialização para leitura
    ],
    denormalizationContext: [
        'groups' => ['treasure:write'] # Seta o grupo de serialização para escrita
    ]
)] # Expõe a entidade como um recurso da API
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
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups('treasure:read')] # Seta o grupo de serialização para leitura
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['treasure:read', 'treasure:write'])] # Seta o grupo de serialização para leitura
    private ?int $value = null;

    #[ORM\Column]
    #[Groups(['treasure:read', 'treasure:write'])] # Seta o grupo de serialização para leitura
    private ?int $coolFactor = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $plunderedAt;

    #[ORM\Column]
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
```

- Note que usamos normalizationContext e denormalizationContext para controlar a serialização e deserialização de um objeto. Em seguida, passamos o grupo de serialização para leitura e escrita.
- Após isso, setamos manualmente o grupo de serialização para cada propriedade da entidade. Note que aplicamos o grupo de serialização para leitura e escrita para alguns métodos.
- Não só isso, criamos alguns métodos novos como `setTextDescription` e `setPlunderedAt` que setam a descrição e a data de criação do recurso, respectivamente.
- Para saber mais sobre serialização, [clique aqui](https://symfony.com/doc/current/serializer.html).

- Podemos aplicar alguns truques com serialização, por exemplo, no caso de description e textDescription, podemos fazer com que ao invés de exibir apenas o nome textDescription na requisição PUT por exemplo, ele exiba description. Para isso, basta adicionar a anotação `#[SerializedName('description')]` acima do método textDescription.
```php
#[Groups('treasure:write')] # Seta o grupo de serialização para escrita
#[SerializedName('description')] # Seta um nome alternativo para o campo
public function setTextDescription(string $description): self
{
    $this->description = nl2br($description);
    
    return $this;
}
```

- Usando o foundry, podemos criar factories para nossas entidades. Para instalar o foundry, basta executar o comando `composer require foundry orm-fixtures --dev` para instalar o foundry e o doctrine fixtures como dependências de desenvolvimento.
- Para criar uma factory, basta executar o comando `sc make:factory` e seguir as instruções. Abaixo temos um exemplo de factory:
```php
<?php

namespace App\Factory;

use App\Entity\DragonTreasure;
use App\Repository\DragonTreasureRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<DragonTreasure>
 *
 * @method        DragonTreasure|Proxy                              create(array|callable $attributes = [])
 * @method static DragonTreasure|Proxy                              createOne(array $attributes = [])
 * @method static DragonTreasure|Proxy                              find(object|array|mixed $criteria)
 * @method static DragonTreasure|Proxy                              findOrCreate(array $attributes)
 * @method static DragonTreasure|Proxy                              first(string $sortedField = 'id')
 * @method static DragonTreasure|Proxy                              last(string $sortedField = 'id')
 * @method static DragonTreasure|Proxy                              random(array $attributes = [])
 * @method static DragonTreasure|Proxy                              randomOrCreate(array $attributes = [])
 * @method static DragonTreasureRepository|ProxyRepositoryDecorator repository()
 * @method static DragonTreasure[]|Proxy[]                          all()
 * @method static DragonTreasure[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static DragonTreasure[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static DragonTreasure[]|Proxy[]                          findBy(array $attributes)
 * @method static DragonTreasure[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static DragonTreasure[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class DragonTreasureFactory extends PersistentProxyObjectFactory
{
    private const TREASURE_NAMES = [
        'pile of gold coins',
        'diamond-encrusted throne',
        'rare magic staff',
        'enchanted sword',
        'set of intricately crafted goblets',
        'collection of ancient tomes',
        'hoard of shiny gemstones',
        'chest filled with priceless works of art',
        'giant pearl',
        'crown made of pure platinum',
        'giant egg (possibly a dragon egg?)',
        'set of ornate armor',
        'set of golden utensils',
        'statue carved from a single block of marble',
        'collection of rare, antique weapons',
        'box of rare, exotic chocolates',
        'set of ornate jewelry',
        'set of rare, antique books',
        'giant ball of yarn',
        'life-sized statue of the dragon itself',
        'collection of old, used toothbrushes',
        'box of mismatched socks',
        'set of outdated electronics (such as CRT TVs or floppy disks)',
        'giant jar of pickles',
        'collection of novelty mugs with silly sayings',
        'pile of old board games',
        'giant slinky',
        'collection of rare, exotic hats'
    ];

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return DragonTreasure::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'coolFactor' => self::faker()->numberBetween(1, 10),
            'description' => self::faker()->paragraph(),
            'isPublished' => self::faker()->boolean(),
            'name' => self::faker()->randomElement(self::TREASURE_NAMES),
            'plunderedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'value' => self::faker()->numberBetween(1000, 1000000),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(DragonTreasure $dragonTreasure): void {})
        ;
    }
}
```

- Na classe AppFixture, adicionamos nossas factories e as persistimos no banco de dados. Abaixo temos um exemplo de como fazer isso:
```php
<?php

namespace App\DataFixtures;

use App\Factory\DragonTreasureFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Cria 40 registros de DragonTreasure
        DragonTreasureFactory::createMany(40);
    }
}
```

- Ou seja, o código `DragonTreasureFactory::createMany(40);` cria 40 registros de DragonTreasure.
- Após isso, basta executar o comando `sc doctrine:fixtures:load` para carregar as fixtures no banco de dados.
- Podemos limitar a quantidade de itens por página, para isso basta adicionar a seguinte configuração:
```php
#[ApiResource(
    shortName: 'treasure', # Seta um nome curto para o endpoint
    description: 'A rare and valuable treasure.', # Seta uma descrição para o recurso
    operations: [ # Seta as operações permitidas para o recurso
        new Get(uriTemplate: '/dragon-plunder/{id}'), # Seta um template de URI para a operação com um parâmetro dinâmico
        new GetCollection(uriTemplate: '/dragon-plunder'), # Seta um template de URI para a operação
        new Post(),
        new Put(),
        new Patch(),
        new Delete(),
    ],
    normalizationContext: [
        'groups' => ['treasure:read'] # Seta o grupo de serialização para leitura
    ],
    denormalizationContext: [
        'groups' => ['treasure:write'] # Seta o grupo de serialização para escrita
    ],
    paginationItemsPerPage: 10, # Seta a quantidade de itens por página
)] # Expõe a entidade como um recurso da API
```

- Note que limitamos a quantidade de itens por página para 10. No entanto, essa limitação é apenas para essa entidade. É possível setar globalmente, para isso recomendo ler a documentação.

- Mas e filtros? Como podemos utilizar filtros em nossa API? Para isso, basta adicionar a seguinte configuração acima da nossa entidade:
```php
// Filtra o recurso por um campo booleano(isPublished)
#[ApiFilter(BooleanFilter::class, properties: ['isPublished'])]
```

- Podemos adicionar esse atributo diretamente na propriedade da entidade, por exemplo:
```php
```php
// Filtra o recurso por um campo booleano(isPublished)
#[ORM\Column]
#[ApiFilter(BooleanFilter::class)]
private bool $isPublished = false;
```

- Podemos adicionar um filtro de pesquisa e utilizar uma estratégia de pesquisa como `start`, 'partial' e `complete`. Abaixo temos um exemplo de como fazer isso:
```php
#[ORM\Column(length: 255)]
#[Groups(['treasure:read', 'treasure:write'])] # Seta o grupo de serialização para leitura
#[ApiFilter(SearchFilter::class, strategy: 'partial')] # Seta um filtro de pesquisa para o campo
private ?string $name = null;
```

- Agora aplicamos a mesma lógica para o campo description:
```php
#[ORM\Column(length: 255)]
#[Groups('treasure:read')] # Seta o grupo de serialização para leitura
#[ApiFilter(SearchFilter::class, strategy: 'partial')] # Seta um filtro de pesquisa para o campo
private ?string $description = null;
```

- Podemos adicionar filtros de intervalo, por exemplo, para o campo value:
```php
#[ORM\Column]
#[Groups(['treasure:read', 'treasure:write'])] # Seta o grupo de serialização para leitura
#[ApiFilter(RangeFilter::class)] # Seta um filtro de intervalo para o campo
private ?int $value = null;
```

- Podemos adicionar outras informações na nossa API, como é o caso do campo shortDescription que é uma descrição curta do recurso. Para isso, basta adicionar a seguinte configuração:
```php
#[Groups('treasure:read')] # Seta o grupo de serialização para leitura
public function getShortDescription(): ?string
{
    // Trunca a descrição para 40 caracteres
    return u($this->description)->truncate(40, '...');
}
```

- Mas faz sentido duas descrições sendo retornadas? Bem, podemos limitar quais campos queremos que sejam retornados, para isso, basta adicionar a seguinte configuração logo acima da classe:
```php
// Habilita o filtro de propriedades que permite selecionar quais campos serão retornados
#[ApiFilter(PropertyFilter::class)]
```

- Podemos adicionar outros formatos além de html, json e jsonld, como é o caso do formato jsonhal. Para isso, basta adicionar o trecho `jsonhal: ['application/hal+json']` no arquivo `config/packages/api_platform.yaml`:
```yaml
api_platform:
    title: Hello API Platform
    version: 1.0.0
    formats:
        jsonld: ['application/ld+json']
        json: ['application/json']
        html: ['text/html']
        jsonhal: ['application/hal+json'] # Adiciona o formato jsonhal
    docs_formats:
        jsonld: ['application/ld+json']
        jsonopenapi: ['application/vnd.openapi+json']
        html: ['text/html']
        jsonhal: ['application/hal+json'] # Adiciona o formato jsonhal
    defaults:
        stateless: true
        cache_headers:
            vary: ['Content-Type', 'Authorization', 'Origin']
        extra_properties:
            standard_put: true
            rfc_7807_compliant_errors: true
    event_listeners_backward_compatibility_layer: false
    keep_legacy_inflector: false
```

- Agora vamos adicionar o formato CSV, no entanto, apenas na nossa entidade DragonTreasure. Para isso, basta adicionar a seguinte configuração:
```php
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
```

- Ou seja, em ApiResource, adicionamos uma chave chamada formats e setamos os valores possíveis de resposta, no caso, jsonld, json, html, jsonhal e csv.