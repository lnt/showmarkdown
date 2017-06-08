<?php

namespace app\handler {

    include_once "lib/autoload.php";
    include_once "AbstractHandler.php";

    use Michelf\Markdown;
    use Michelf\MarkdownExtra;

    class index extends AbstractHandler
    {

        public $file = null;
        public $parse = true;
        public $domain = "http://raw.githubusercontent.com/";

        public function invoke()
        {
            $content = file_get_contents($this->domain.$this->file);
            if($this->parse){
                $content = MarkdownExtra::defaultTransform($content);
            }
            echo $content;
        }

    }

    (new index())->execute();
}
