<?php

namespace Backend\Helpers;

class Response
{
    private $status = 200;

    public function status(int $code)
    {
        $this->status = $code;
        return $this;
    }

    public function toJSON($data = [])
    {
        http_response_code($this->status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function filePassthru(string $filename)
    {
        $handle = fopen($filename, "rb");
        $imageSize = getimagesize($filename);

        header("Content-Type: " . $imageSize['mime']);
        header("Content-Length: " . filesize($filename)); 
        fpassthru($handle);
    }
}