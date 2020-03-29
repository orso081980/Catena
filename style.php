<?php
require_once __DIR__ . "/scss.inc.php";
$scss = new scssc();
$scssIn = file_get_contents(__DIR__ . '/scss/style.scss');
$cssOut = $scss->compile($scssIn);
file_put_contents(__DIR__ . '/css/style.css', $cssOut);