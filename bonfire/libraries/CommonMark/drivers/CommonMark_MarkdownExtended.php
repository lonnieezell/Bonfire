<?php

class CommonMark_MarkdownExtended
{
    protected $parser = null;

    protected function init()
    {
        get_instance()->load->helper('markdown_extended');
        $this->parser = new MarkdownExtraExtended_Parser();
    }

    public function parse($text)
    {
        if ($this->parser === null) {
            $this->init();
        }

        return $this->parser->transform($text);
    }
}
