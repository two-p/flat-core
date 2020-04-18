<?php


namespace App\module;


class ParentGalleryModule implements module
{
    private $path;
    private $data = [];
    public function __construct($path,$albums)
    {
        $tmp= explode("/", $path);
        array_pop($tmp);
        $path = implode('/',$tmp);
        $this->path = $path;
        foreach ($albums as $album){
            $album['path'] = str_replace(content_path, '', $path) . DIRECTORY_SEPARATOR . $album['path'];
            array_push($this->data,$album);
        }
    }

    public function getData()
    {
        return $this->data;
    }
}
