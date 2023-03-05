<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;

// si on a besoin de cette propriété dans une entity il suffira de mettre
// dans nom_entity.phpen première ligne dans la classe nom_entity : use SlugTrait
// ainsi que d'ajouter au tout début : use App\entity\Trait\SlugTrait; (mettre un ;)
trait SlugTrait
{
    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}