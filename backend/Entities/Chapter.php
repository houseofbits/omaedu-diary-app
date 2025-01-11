<?php

namespace Backend\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Backend\Entities\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Backend\Entities\Diary;

/**
 * @ORM\Entity
 * @ORM\Table(name="chapters")
 */
class Chapter
{
    use TimestampableTrait;

    public function __construct(
    ) {
        $this->setCreatedAt(new DateTime("now"));
        $this->images = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected int|null $id = null;

    /**
     *  @ORM\ManyToOne(targetEntity="Diary") 
     *  @ORM\JoinColumn(name="diary_id", referencedColumnName="id")
     */
    protected Diary|null $diary = null;    

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected string $title = "";

    /**
     * @ORM\Column(type="text")
     */
    protected string $story = "";

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $location = "";

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $period = "";

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="chapter")
     */
    protected Collection $images;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    protected ?array $layouts = null;

    
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

    public function setDiary(Diary $diary): Chapter
    {
        $this->diary = $diary;
        return $this;
    }    

    public function setLocation(string $location): Chapter
    {
        $this->location = $location;
        return $this;
    }

    public function setPeriod(string $period): Chapter
    {
        $this->period = $period;
        return $this;
    }

    public function setLayouts(array $layouts): Chapter
    {
        $this->layouts = $layouts;
        return $this;
    }

    public function getId(): ?int
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

    public function getPeriod(): string
    {
        return $this->period;
    }

    public function getDiary(): ?Diary
    {
        return $this->diary;
    }    

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function getLayouts(): array
    {
        return $this->layouts ?? [];
    }    
}