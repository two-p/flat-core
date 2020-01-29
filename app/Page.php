<?php

namespace App;


use cebe\markdown\GithubMarkdown;
use VKBansal\FrontMatter\Parser;
use erusev\ParsedownExtra;

/**
 * Page model constructed from a YAML file
 *
 * @package App
 */
class Page
{

    private $filePath;
    private $parsedData;

    /**
     * @param $path
     */
    public function __construct($path)
    {
        $this->filePath = $path;
        if(!$this->parsedData){
            $this->parsedData = Parser::parse(file_get_contents($this->filePath));
        }
    }

    /**
     * @param $property
     * @return mixed
     */
    public function __get($key){


        if($key === "content"){
            $pathinfo = explode('.', pathinfo($this->filePath, PATHINFO_FILENAME));
            $type = end($pathinfo);
            $method = "parse_$type";
            return $this->$method($this->parsedData->getContent());
        }
        if(!isset($this->parsedData->getConfig()[$key])){
            return null;
        }
        if(is_array($this->parsedData->getConfig()[$key])){
            return $this->parsedData->getConfig()[$key];
        }
        return nl2br($this->parsedData->getConfig()[$key]);

    }
    public function __isset($key)
    {
        return '__isset';
    }

    /**
     * Return the URL for the page based on content path
     * @return string
     */
    public function getUrl(){
        $path = str_replace(content_path, '', $this->filePath);
        $path = current(explode('.', $path));
        $path = str_replace('index', '', $path);
        return $path;
    }

    /**
     * Return the name of the page using . as separators, used for router
     * @return string
     */
    public function getName(){
        $name = str_replace('/', '.', $this->getUrl());
        $name = trim($name, '.');
        if(empty($name)){
            return 'home';
        }
        return $name;
    }
    private function parse_markdown($content){
        //$Extra = new ParsedownExtra();
        // $parser = new ParsedownExtra();
        $parser = new \ParsedownExtra();
        $parser->enableNewlines = true;
        return $parser->text($content);
    }

    //Called in function __get()
    private function parse_html($content){
        return $content;
    }

    //Called in function __get()
    public function getLayout(){
        $method = '__get';
        $layout = $this->$method('layout');
        if($layout == "")
            $layout = 'default';

        return $layout;
    }
}
