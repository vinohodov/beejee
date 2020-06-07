<?php

namespace lib;

use \Twig\Loader\FilesystemLoader;
use \Twig\Environment;

class Template
{
    public function send(String $tmpl, array $data = []) {
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);
        echo $twig->render($tmpl . '.html', $data);
    }
}
