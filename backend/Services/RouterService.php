<?php

namespace Backend\Services;

use Backend\Helpers\Request;
use Backend\Helpers\Response;

class RouterService {
    public function __construct(
        private Request $request,        
    )
    {    }

    public function GET(string $target, callable $callback, bool $withId = false) {
        if ($this->validate($target, $withId) && $this->request->isGet()) {
            $callback($this->request, new Response());
        }
    }

    public function DELETE(string $target, callable $callback, bool $withId = false) {
        if ($this->validate($target, $withId) && $this->request->isDelete()) {
            $callback($this->request, new Response());
        }
    }    

    public function POST(string $target, callable $callback, bool $withId = false) {
        if ($this->validate($target, $withId) && $this->request->isPost()) {
                        $callback($this->request, new Response());
        }
    }     

    public function PUT(string $target, callable $callback, bool $withId = false) {
        if ($this->validate($target, $withId) && $this->request->isPut()) {
            $callback($this->request, new Response());
        }
    }   
    
    private function validate(string $target, bool $withId): bool {
        if ($withId !== $this->request->hasTargetId()) {
            return false;
        }
        
        if ($target === $this->request->target && $this->request->isValid()) {
            return true;
        }

        return false;
    }
};
