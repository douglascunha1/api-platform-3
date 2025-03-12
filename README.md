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
- 