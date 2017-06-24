<?php

namespace app\handler {

    include_once "lib/autoload.php";
    include_once "app/handler/AbstractHandler.php";

    use Michelf\Markdown;
    use Michelf\MarkdownExtra;
    use Highlight\Highlighter;

    class index extends AbstractHandler
    {

        public $__q__ = "lnt/snipit/master/README.md";
        public $parse = true;
        public $domain = "http://raw.githubusercontent.com/";
        public $embed = null;
        public $lang = null;
        public $view = null;
        public $md = null;

        public function getHighlight($file)
        {
            $content = file_get_contents($file);
            $hl = new Highlighter();
            $r = null;
            if (empty($this->lang)) {
                $hl->setAutodetectLanguages(array("ruby", "python", "perl", "javascript", "json", "css"));
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
                printf("<link rel=\"stylesheet\" href=\"/app/styles/default.css\">
                        <div><pre class=\"hljs %s\" >%s</pre></div>", $r->language, $r->value);
                exit();
            } else if (!empty($this->embed)) {
                header("Content-Type: application/javascript");
                $r = $this->getHighlight($this->embed);
                printf("document.write('<link rel=\"stylesheet\" href=\"/app/styles/default.css\"><div><pre class=\"hljs %s\" >'+atob('%s')+'</pre></div>')", $r->language, base64_encode($r->value));
                exit();
            } else if (!empty($this->md)) {
                $content = file_get_contents($this->md);
                $content = MarkdownExtra::defaultTransform($content);
                echo $content;
                exit("<link rel=\"stylesheet\" href=\"/app/styles/default.css\">");
            } else if (!empty($this->__q__)) {
                $git_file = $this->__q__;
                $match = array();
                if(preg_match("/(.+)\/(.+)\/blob\/(.+)\/(.+)\.md$/",$this->__q__,$match)){
                    $git_file = sprintf("%s/%s/%s/%s.md",$match[1],$match[2],$match[3],$match[4]);
                }
               // exit($git_file);
                $content = file_get_contents($this->domain . $git_file);
                $content = MarkdownExtra::defaultTransform($content);
                //https://github.com/boilerplatez/docs/master/markdown/xampp/ENV.md
                //$content = preg_replace('/XAMP/', "$$$$",$content);
                $content = preg_replace('/\<a([^>]*) href=(\'|\")https?\:\/\/github\.com([^>]*)\2\s?([^>]*)\>/i', "<a$1 href=$2$3$2 $4>",$content);
                echo $content;
                exit("<link rel=\"stylesheet\" href=\"/app/styles/default.css\">");
            }
        }

    }

    (new index())->execute();
}
