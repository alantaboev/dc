<?php

namespace Differ\Cli;

use Docopt;
use function Differ\Differ\genDiff;

function run()
{
    $doc = <<<DOC
    gendiff -h
    
    Generate diff
    
    Usage:
      gendiff (-h|--help)
      gendiff (-v|--version)
      gendiff [--format <fmt>] <firstFile> <secondFile>
    
    Options:
      -h --help                     Show this screen
      -v --version                  Show version
      --format <fmt>                Report format [default: stylish]
DOC;

    $args = Docopt::handle($doc);

    try {
        print_r(genDiff($args['<firstFile>'], $args['<secondFile>'], $args['--format']));
    } catch (\Exception $e) {
        print_r($e->getMessage());
        die();
    }
}
