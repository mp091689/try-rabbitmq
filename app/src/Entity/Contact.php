<?php
declare(strict_types=1);
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact
{
    use EntityHelper;

    /**
     * Keep "id" for "joins" it takes us better performance than using "uuid"
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Generates manually before sand to queue.
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=50)
     * @Constraints\NotBlank(message="Fist name can not be blank")
     * @Constraints\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "First name must be at least {{ limit }} characters long",
     *     maxMessage = "First name cannot be longer than {{ limit }} characters"
     * )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=50)
     * @Constraints\NotBlank(message="Last name can not be blank")
     * @Constraints\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "Last name must be at least {{ limit }} characters long",
     *     maxMessage = "Last name cannot be longer than {{ limit }} characters"
     * )
     */
    private $lastName;

    /**
     * @ORM\Column(type="array")
     * @Constraints\NotBlank(message="Phone numbers can not be blank")
     * @Constraints\All({
     *      @Constraints\NotBlank,
     *      @Constraints\Regex(
     *          pattern="/^\d{3} \d{3}-\d{4}$/",
     *          message="Wrang phone number format. Expected format: xxx xxx-xxxx"
     *      )
     * })
     */
    private $phoneNumbers;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     *
     * @return self
     * @throws \Exception
     */
    public function setUuid(string $uuid): self
    {
        if (empty($this->uuid)) {
            $this->uuid = $uuid;
        }

        return $this;
    }

    /**
     * @return Contact
     * @throws \Exception
     */
    public function generateUuid(): self
    {
        if (empty($this->uuid)) {
            $this->uuid = Uuid::uuid4()->toString();
        }

        return $this;
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

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return Contact
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return array
     */
    public function getPhoneNumbers(): array
    {
        return $this->phoneNumbers;
    }

    /**
     * @param array $phoneNumbers
     *
     * @return Contact
     */
    public function setPhoneNumbers(array $phoneNumbers): self
    {
        $this->phoneNumbers = $phoneNumbers;

        return $this;
    }
}
