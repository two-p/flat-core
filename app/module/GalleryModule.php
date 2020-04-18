<?php
namespace App\module;


class GalleryModule implements module
{
    private $path;
    private $data=[];
    public function __construct($path)
    {
        $tmp = explode("/", $path);
        array_pop($tmp);
        $path = implode('/',$tmp);
        $this->path = $path;
        foreach ( glob($path. DIRECTORY_SEPARATOR.'*.{jpg,JPG,jpeg,png}',GLOB_BRACE)  as $absolutePath){
            $tmp = explode('/',$absolutePath);
            $filename = array_pop($tmp);
            $filename = explode('.',$filename)[0];
            if($filename != "cover" && $this->checkNotthumbnail($filename)){
                array_push($this->data, str_replace(built_img_path, '', $path)."/".$filename);
            }
        }
    }

    public function getData()
    {
        return $this->data;
    }
    private function checkNotthumbnail($filename){
        return strstr($filename, 'thumbnail') == "";
    }
}
