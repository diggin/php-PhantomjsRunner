php-PhantomjsRunner
===================

phantomjs - "minimalistic headless WebKit-based JavaScript-driven tool"

Requirements
------------
  - PHP 5.3

Usage
----
set your phantomjs path:
    require_once "Diggin/Phantomjs.php";
    $phantomjs = new Diggin\Phantomjs('/path/to/phantomjs/bin/phantomjs');
    $phantomjs->getHtml($url);


