<?php

namespace Backend\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Backend\Entities\Traits\TimestampableTrait;
use Backend\Structures\SettingsStructure;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    use TimestampableTrait;

    public function __construct(
    ) {
        $this->setCreatedAt(new DateTime("now"));    
        $this->diaries = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", length=150, nullable=false, name="name")
     */
    protected string $userName = "";

    /**
     * @ORM\Column(type="string", length=255, name="diary_title")
     */
    protected string $diaryTitle = "";

    /**
     * @ORM\Column(type="json")
     */
    protected array $settings = [];

    /**
     * @ORM\OneToMany(targetEntity="Diary", mappedBy="user")
     */
    private Collection $diaries;

    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }

    public function setUserName(string $userName): User
    {
        $this->userName = $userName;
        return $this;
    }   
    
    public function setDiaryTitle(string $diaryTitle): User
    {
        $this->diaryTitle = $diaryTitle;
        return $this;
    } 
    
    public function setSettings(SettingsStructure $settings): User
    {
        $this->settings = (array) $settings;
        return $this;
    }     

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getDiaryTitle(): string
    {
        return $this->diaryTitle;
    }  
    
    public function getSettings(): array
    {
        return $this->settings;
    }   

    /**
     * @return Collection<int, Diary>
     */
    public function getDiaries(): Collection
    {
        return $this->diaries;
    }    
}