<?php

namespace Backend\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Backend\Entities\Traits\TimestampableTrait;
use Backend\Structures\DiarySettingsStructure;

/**
 * @ORM\Entity
 * @ORM\Table(name="diaries")
 */
class Diary
{
    use TimestampableTrait;

    public function __construct(
    ) {
        $this->chapters = new ArrayCollection();
        $this->healthRecords = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected int|null $id = null;

    /**
     *  @ORM\ManyToOne(targetEntity="User") 
     *  @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected User|null $user = null;

    /**
     * @ORM\Column(type="string", length=80, name="type")
     */
    protected string $type = "";

    /**
     * @ORM\Column(type="string", length=255, name="diary_title")
     */
    protected string $diaryTitle = "";

    /**
     * @ORM\Column(type="text")
     */
    protected string $diaryDescription = "";

    /**
     * @ORM\Column(type="json")
     */
    protected array $settings = [];

    /**
     * @ORM\OneToMany(targetEntity="Chapter", mappedBy="diary")
     */
    private Collection $chapters;

    /**
     * @ORM\OneToMany(targetEntity="HealthRecord", mappedBy="diary")
     */
    private Collection $healthRecords;    

    public function setId(int $id): Diary
    {
        $this->id = $id;
        return $this;
    }

    public function setUser(User $user): Diary
    {
        $this->user = $user;
        return $this;
    }

    public function setType(string $type): Diary
    {
        $this->type = $type;
        return $this;
    } 

    public function setDiaryTitle(string $diaryTitle): Diary
    {
        $this->diaryTitle = $diaryTitle;
        return $this;
    } 

    public function setDiaryDescription(string $diaryDescription): Diary
    {
        $this->diaryDescription = $diaryDescription;
        return $this;
    }     
    
    public function setSettings(array $settings): Diary
    {
        $this->settings = $settings;
        return $this;
    }     

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }    

    public function getType(): string
    {
        return $this->type;
    }    

    public function getDiaryTitle(): string
    {
        return $this->diaryTitle;
    }  

    public function getDiaryDescription(): string
    {
        return $this->diaryDescription;
    }      
    
    public function getSettings(): array
    {
        return $this->settings;
    }   

    /**
     * @return Collection<int, Chapter>
     */
    public function getChapters(): Collection
    {
        return $this->chapters;
    }   
    
    /**
     * @return Collection<int, HealthRecord>
     */
    public function getHealthRecords(): Collection
    {
        return $this->healthRecords;
    }       
    
    public function isDiary(): bool {
        return $this->type == "diary";
    }

    public function toJson() {
        return [
            'id' => $this->id,
            'title' => $this->diaryTitle,
            'description' => $this->diaryDescription,
            'settings' => $this->settings,
        ];
    }
}