<?php

namespace Backend\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Backend\Entities\Traits\TimestampableTrait;
use Backend\Entities\Diary;

/**
 * @ORM\Entity
 * @ORM\Table(name="health_records")
 */
class HealthRecord
{
    use TimestampableTrait;

    public function __construct(
    ) {
        $this->setCreatedAt(new DateTime("now"));
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
     * @ORM\Column(type="json", nullable=true)
     */
    protected ?array $data = null;


    public function setId(int $id): HealthRecord
    {
        $this->id = $id;
        return $this;
    }

    public function setDiary(Diary $diary): HealthRecord
    {
        $this->diary = $diary;
        return $this;
    }

    public function setData(array $data): HealthRecord
    {
        $this->data = $data;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiary(): ?Diary
    {
        return $this->diary;
    }

    public function getData(): array
    {
        return $this->data ?? [];
    }
}