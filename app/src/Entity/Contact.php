<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 * @UniqueEntity("firstName", message="First name is already exists")
 */
class Contact
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Constraints\NotBlank(message="Fist name can not be blank")
     * @Constraints\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "Your first name must be at least {{ limit }} characters long",
     *     maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $firstName = "";

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return self
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

}
