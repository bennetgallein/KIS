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
    private $baseperm;
    var $priority;

    private $includeables;
    private $navs;
    private $dashboard;
    private $basepath;

    public function __construct($json) {
        $this->name = $json['name'];
        $this->version = $json['version'];
        $this->authors = $json['authors'];
        $this->navs = isset($json['navs']) ? $json['navs'] : null;
        $this->dashboard = isset($json['dashboards']) ? $json['dashboards'] : null;
        $this->includeables = isset($json['includeables']) ? $json['includeables'] : null;
        $this->baseperm = $json['baseperm'];
        $this->priority = $json['priority'];
        $this->basepath = $json['basepath'];
    }

    public function getIncludeable($name) {
        foreach ($this->includeables as $include) {
            if ($name == $include['name']) {
                return $include;
            }
        }
        return false;
    }
    public function getBasepath() {
        return $this->basepath;
    }
    public function getNavs() {
        return $this->navs;
    }

    public function getBaseperm() {
        return $this->baseperm;
    }

    public function getDashboards() {
        return $this->dashboard;
    }

    public function getIncludeables() {
        return $this->includeables;
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

    static function cmp($a, $b) {
        $al = ($a->priority);
        $bl = ($b->priority);
        if ($al == $bl) {
            return 0;
        }
        return ($al > $bl) ? +1 : -1;
    }
}
