<?php

class CommonMark_Markdown
{
    protected $parser = null;

    public function init()
    {
        if (get_instance()->config->item('composer_autoload') === false) {
            $paths = array(
                APPPATH . 'vendor/michelf/php-markdown/Michelf',
                APPPATH . '../vendor/michelf/php-markdown/Michelf',
                APPPATH . 'third_party/michelf/php-markdown/Michelf',
                APPPATH . 'third_party/Michelf',
            );
            $filename = 'Markdown.inc.php';
            $found = false;
            foreach ($paths as $path) {
                if (file_exists("{$path}/{$filename}")) {
                    require_once("{$path}/{$filename}");
                    $found = true;
                    break;
                }
            }

            if (! $found) {
                log_message('error', 'CommonMark_Markdown: Could not find Markdown');
                return false;
            }
        }

        $this->parser = new \Michelf\Markdown();
    }

    public function parse($text)
    {
        if ($this->parser === null && $this->init() === false) {
            return;
        }

        return $this->parser->defaultTransform($text);
    }
}
