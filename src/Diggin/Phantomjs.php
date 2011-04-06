<?php

namespace Diggin;

class Phantomjs
{
    /**
     * phantomjs Command-line options
     * @see http://code.google.com/p/phantomjs/wiki/Interface
     *
     * @var array
     */
    private $options = array(
        'load-images' => 'no',
        'load-plugins' => 'no',
    );

    /**
     * writable properties
     */
    private $config = array(
        'userAgent' => __CLASS__
    );

    /**
     * phantomjs binary path
     * @var string
     */
    private $bin;

    private $messages = array();
    private $ignoreLineHandler;

    public function __construct($bin)
    {
        $this->bin = $bin;
    }

    public function setOptions(array $options)
    {
        foreach ($options as $key => $v) {
            if (is_string($v)) {
                switch (strtolower($v)) {
                    case 'yes':
                    case 'true':
                        $v = true;
                        break;
                    case 'no':
                    case 'false':
                        $v = false;
                }
            }
            $v = (boolean) $v;
            if (!is_bool($v)) continue;

            $this->options[strtolower($key)] = ($v === true) ? 'yes' : 'no';
        }
    }

    public function getOptionsAsString()
    {
        $ret = '';
        foreach ($this->options as $key => $v) {
            $ret .= ' --'.$key.'='.$v;
        }
        return $ret;
    }

    public function execute($script, $argsString = '')
    {
        $bin = $this->bin;
        $options = $this->getOptionsAsString();

        return shell_exec("$bin$options $script $argsString");
    }

    /**
     * get response body content
     */
    public function getHtml($url)
    {
        $ua = $this->config['userAgent'];
        $argsString = "$ua $url";

        $output = $this->execute(__DIR__.'/Phantomjs/_js/'.'httpget.js', $argsString);
        $content = $this->filterOutput($output);

        return $content;
    }

    public function filterOutput($output)
    {
        $content = '';
        $not_ignored = false;
        foreach(preg_split('/\n/s', $output) as $line) {
            if (!$not_ignored) {
                if ($this->isIgnoreLine($line)) {
                    $this->messages[] = $line;
                    continue;
                }
                $not_ignored = true;
            }
            $content .= $line."\n";
        } 

        return $content;
    }

    public function isIgnoreLine($line)
    {
        $callback = $this->getIgnoreLineHandler();
        return $callback($line);
    }

    public function getIgnoreLineHandler()
    {
        if (!$this->ignoreLineHandler) {
            $this->ignoreLineHandler = function ($line) {
                return !preg_match('/^\s*</', $line);
            };
        }
        return $this->ignoreLineHandler;
    }

    public function setIgnoreLineHandler($handler)
    {
        $this->ignoreLineHandler = $handler;
    }

    public function getLastMessages()
    {
        return $this->messages;
    }
}
