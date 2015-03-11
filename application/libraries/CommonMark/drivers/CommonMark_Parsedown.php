<?php

class Commonmark_Parsedown extends CI_Driver
{
    protected $parser = null;

    protected function init()
    {
        get_instance()->load->library('Parsedown');
        $this->parser = new Parsedown();
    }

    public function parse($text)
    {
        if ($this->parser === null) {
            $this->init();
        }

        return $this->parser->text($text);
    }
}
