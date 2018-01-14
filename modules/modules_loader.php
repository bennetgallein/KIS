<?php

/**
 * Created by PhpStorm.
 * User: Bennet
 * Date: 1/14/2018
 * Time: 12:33 AM
 */
class ModuleLoader {

    private $modules;

    public function __construct() {
        $this->modules = array();
        $dir = new DirectoryIterator(dirname(__FILE__));
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDir()) {
                if (pathinfo($fileinfo->getPathname())['extension'] == "json") {
                    $this->createModuleByJSON($fileinfo->getPath(), $fileinfo->getFilename());
                }
            }
        }
    }

    public function createModuleByJSON($path, $filename) {
        $json = json_decode(file_get_contents($path . "/" . $filename), true);
        array_push($this->modules, new Module($json));
    }
    public function getModules() {
        return $this->modules;
    }
}

class Module {

    private $name;
    private $version;
    private $authors;

    private $navs;
    private $dashboard;

    public function __construct($json) {
        $this->name = $json['name'];
        $this->version = $json['version'];
        $this->authors = $json['authors'];
        $this->navs = $json['navs'];
        $this->dashboard = $json['dashboard'];
    }

    public function getNavs() {
        return $this->navs;
    }

    public function getDashboards() {
        return $this->dashboard;
    }

    public function getName() {
        return $this->name;
    }

    public function getVersion() {
        return $this->version;
    }

    public function getAuthors() {
        return $this->authors;
    }

}