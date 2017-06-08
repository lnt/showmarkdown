<?php

namespace app\handler {

    include_once "lib/autoload.php";
    include_once "app/handler/AbstractHandler.php";

    use Michelf\Markdown;
    use Michelf\MarkdownExtra;
    use Highlight\Highlighter;

    class index extends AbstractHandler
    {

        public $__q__ = "boilerplatez/docs/master/markdown/xampp/MAC.md";
        public $parse = true;
        public $domain = "http://raw.githubusercontent.com/";
        public $empbed = null;
        public $lang = null;
        public $view = null;

        public function getHighlight($file)
        {
            $content = file_get_contents($file);
            $hl = new Highlighter();
            $r = null;
            if (empty($this->lang)) {
                $hl->setAutodetectLanguages(array("ruby", "python", "perl", "javascript", "json"));
                $r = $hl->highlightAuto($content);
            } else {
                $r = $hl->highlight($this->lang, $content);
            }
            return $r;
        }

        public function invoke()
        {
            if (!empty($this->view)) {
                $r = $this->getHighlight($this->view);
                printf("<link rel=\"stylesheet\" href=\"/lib/scrivo/highlight.php/styles/default.css\">
                        <div><pre class=\"hljs %s\" >%s</pre></div>", $r->language, $r->value);
            } else if (!empty($this->empbed)) {
                header("Content-Type: application/javascript");
                $r = $this->getHighlight($this->empbed);
                printf("document.write('<link rel=\"stylesheet\" href=\"/lib/scrivo/highlight.php/styles/default.css\"><div><pre class=\"hljs %s\" >'+atob('%s')+'</pre></div>')", $r->language, base64_encode($r->value));
            } else if (empty($this->__q__)) {
                $content = file_get_contents($this->domain . $this->__q__);
                if ($this->parse) {
                    $content = MarkdownExtra::defaultTransform($content);
                }
                echo $content;
                exit();
            }
        }

    }

    (new index())->execute();
}
