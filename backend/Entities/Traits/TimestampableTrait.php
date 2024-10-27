<?php

namespace Backend\Entities\Traits;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

trait TimestampableTrait
{
    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"default": "CURRENT_TIMESTAMP"})
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true, options={"default": "CURRENT_TIMESTAMP"})
     */
    private DateTime $updatedAt;

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}