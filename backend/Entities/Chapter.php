<?php

namespace Backend\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Backend\Entities\Traits\SoftDeleteTrait;
use Backend\Entities\Traits\TimestampableTrait;
use Backend\Structures\SettingsStructure;

/**
 * @ORM\Entity
 * @ORM\Table(name="chapters")
 */
class Chapter
{
    use TimestampableTrait;
    use SoftDeleteTrait;

    public function __construct(
    ) {
        $this->setCreatedAt(new DateTime("now"));
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected int $id;

    /**
     *  @ORM\ManyToOne(targetEntity="User") 
     *  @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private User|null $user = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected string $title = "";

    /**
     * @ORM\Column(type="string")
     */
    protected string $story = "";

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $location = "";

    public function setId(int $id): Chapter
    {
        $this->id = $id;
        return $this;
    }

    public function setTitle(string $title): Chapter
    {
        $this->title = $title;
        return $this;
    }

    public function setStory(string $story): Chapter
    {
        $this->story = $story;
        return $this;
    }

    public function setUser(User $user): Chapter
    {
        $this->user = $user;
        return $this;
    }

    public function setLocation(string $location): Chapter
    {
        $this->location = $location;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getStory(): string
    {
        return $this->story;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }    
}