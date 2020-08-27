<?php

namespace Differ\Run;

use Docopt;

function run()
{
    $doc = <<<DOC
    gendiff -h
    
    Generate diff
    
    Usage:
      gendiff (-h|--help)
      gendiff (-v|--version)
    
    Options:
      -h --help                     Show this screen
      -v --version                  Show version
DOC;

    $args = Docopt::handle($doc, array('version'=>'Naval Fate 2.0'));
    foreach ($args as $k => $v) {
        echo $k.': '.json_encode($v).PHP_EOL;
    }
}