<?php
ini_set("include_path", dirname(__FILE__)."/src/" . PATH_SEPARATOR . ini_get("include_path"));
require_once "Diggin/Phantomjs.php";

$phantomjs = new Diggin\Phantomjs('/path/to/phantomjs/bin/phantomjs');
$html = $phantomjs->getHtml($argv[1]);

var_dump($html);
var_dump($phantomjs->getLastMessages());

