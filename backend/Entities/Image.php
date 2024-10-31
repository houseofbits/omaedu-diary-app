<?php

namespace Backend\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Backend\Entities\Traits\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="images")
 */
class Image
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
     *  @ORM\ManyToOne(targetEntity="Chapter") 
     *  @ORM\JoinColumn(name="chapter_id", referencedColumnName="id")
     */
    protected Chapter|null $chapter = null;

    /**
     * @ORM\Column(type="smallint", nullable=false, name="order_by")
     */
    protected int $order = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, name="original_file_name")
     */
    protected string $fileName = "";

    /**
     * @ORM\Column(type="string", length=50, nullable=false, name="extension")
     */
    protected string $extension = "";

    public function setId(int $id): Image
    {
        $this->id = $id;
        return $this;
    }

    public function setOrder(int $order): Image
    {
        $this->order = $order;
        return $this;
    }

    public function setFileName(string $fileName): Image
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function setExtension(string $extension): Image
    {
        $this->extension = $extension;
        return $this;
    }

    public function setChapter(Chapter $chapter): Image
    {
        $this->chapter = $chapter;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }


    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }
}