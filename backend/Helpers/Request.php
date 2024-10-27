<?php

namespace Backend\Helpers;

use Backend\Services\UserService;
use Backend\Entities\User;

class Request
{
    public string $reqMethod;
    public string $contentType;
    public ?string $target;
    public ?string $targetId;
    public ?User $user = null;

    public function __construct(
        private UserService $userService,
    )
    {   
        $this->reqMethod = trim($_SERVER['REQUEST_METHOD']);
        $this->contentType = !empty($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        $this->target = $_REQUEST["target"] ?? null;
        $this->targetId = $_REQUEST["id"] ?? null;
        $this->user = $this->userService->getUser($_REQUEST["credentials"] ?? '');
    }

    public function getJSON()
    {
        if ($this->reqMethod !== 'POST' && $this->reqMethod !== 'PUT') {
            return [];
        }

        if (strcasecmp($this->contentType, 'application/json') !== 0) {
            return [];
        }

        $content = trim(file_get_contents("php://input"));
        $decoded = json_decode($content, true);

        return $decoded;
    }

    public function isGet(): bool {
        return $this->reqMethod === "GET";
    }

    public function isDelete(): bool {
        return $this->reqMethod === "DELETE";
    }    

    public function isPost(): bool {
        return $this->reqMethod === "POST";
    } 

    public function isPut(): bool {
        return $this->reqMethod === "PUT";
    }    
    
    public function hasTargetId(): bool {
        return $this->targetId !== null;
    }

    public function isValid(): bool {
        return $this->user !== null;
    }
}