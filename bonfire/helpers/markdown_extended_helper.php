<?php

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License.
 * @link      http://cibonfire.com
 * @since     0.7.2
 * @filesource
 */

/**
 * Markdown Extra Extended Helper
 *
 * @deprecated since 0.9.0. Use Bonfire's CommonMark library with one of the drivers
 * in /application/libraries/CommonMark/drivers/
 * @see https://github.com/ci-bonfire/Bonfire/blob/develop/bonfire/docs/commonmark.md
 *
 * This is here primarily for backwards compatibility, and has been updated only
 * because it should be kept up to date until it is removed.
 *
 * This is PHP Markdown Extra Extended, updated with commits through 2015-06-29:
 * https://github.com/egil/php-markdown-extra-extended/commit/916b2f0bfe6d92bf5f6cf36dcfed2e5f73a9920a
 *
 * @package Bonfire\Helpers\markdown_extended_helper
 * @author  Bonfire Dev Team
 * @author  Egil Hansen <http://egilhansen.com>
 * @link    https://github.com/egil/php-markdown-extra-extended
 * @link    https://github.com/ci-bonfire/Bonfire/blob/develop/bonfire/docs/commonmark.md
 */

require_once('markdown_helper.php');
define('MARKDOWNEXTRAEXTENDED_VERSION', "0.3");

function MarkdownExtended($text, $default_classes = array())
{
    $parser = new MarkdownExtraExtended_Parser($default_classes);
    return $parser->transform($text);
}

class MarkdownExtraExtended_Parser extends MarkdownExtra_Parser
{
    # Tags that are always treated as block tags:
    var $block_tags_re = 'figure|figcaption|p|div|h[1-6]|blockquote|pre|table|dl|ol|ul|address|form|fieldset|iframe|hr|legend';
    var $default_classes;

    function MarkdownExtraExtended_Parser($default_classes = array())
    {
        $default_classes = $default_classes;

        $this->block_gamut += array(
            "doFencedFigures" => 7,
        );
        $this->span_gamut += array(
            "doStrikethroughs" => -35
        );
        parent::MarkdownExtra_Parser();
    }

    function transform($text)
    {
        $text = parent::transform($text);
        return $text;
    }

    function doHardBreaks($text)
    {
        # Do hard breaks:
        # EXTENDED: changed to allow breaks without two spaces and just one new line
        # original code /* return preg_replace_callback('/ {2,}\n/', */
        return preg_replace_callback(
            '/ *\n/',
            array(&$this, '_doHardBreaks_callback'),
            $text
        );
    }

    function doBlockQuotes($text)
    {
        $text = preg_replace_callback(
            '/
            (?>^[ ]*>[ ]?
                (?:\((.+?)\))?
                [ ]*(.+\n(?:.+\n)*)
            )+
            /xm',
            array(&$this, '_doBlockQuotes_callback'),
            $text
        );

        return $text;
    }

    function _doBlockQuotes_callback($matches)
    {
        $cite = $matches[1];
        $bq = '> ' . $matches[2];
        # trim one level of quoting - trim whitespace-only lines
        $bq = preg_replace('/^[ ]*>[ ]?|^[ ]+$/m', '', $bq);
        $bq = $this->runBlockGamut($bq);        # recurse

        $bq = preg_replace('/^/m', "  ", $bq);
        # These leading spaces cause problem with <pre> content,
        # so we need to fix that:
        $bq = preg_replace_callback(
            '{(\s*<pre>.+?</pre>)}sx',
            array(&$this, '_doBlockQuotes_callback2'),
            $bq
        );

        $res = "<blockquote";
        $res .= empty($cite) ? ">" : " cite=\"$cite\">";
        $res .= "\n$bq\n</blockquote>";
        return "\n". $this->hashBlock($res)."\n\n";
    }

    function doFencedCodeBlocks($text)
    {
        $less_than_tab = $this->tab_width;

        $text = preg_replace_callback(
            '{
                (?:\n|\A)
                # 1: Opening marker
                (
                    ~{3,}|`{3,} # Marker: three tilde or more.
                )

                [ ]?(\w+)?(?:,[ ]?(\d+))?[ ]* \n # Whitespace and newline following marker.

                # 3: Content
                (
                    (?>
                        (?!\1 [ ]* \n)  # Not a closing marker.
                        .*\n+
                    )+
                )

                # Closing marker.
                \1 [ ]* \n
            }xm',
            array(&$this, '_doFencedCodeBlocks_callback'),
            $text
        );

        return $text;
    }

    function _doFencedCodeBlocks_callback($matches)
    {
        $codeblock = $matches[4];
        $codeblock = htmlspecialchars($codeblock, ENT_NOQUOTES);
        $codeblock = preg_replace_callback(
            '/^\n+/',
            array(&$this, '_doFencedCodeBlocks_newlines'),
            $codeblock
        );
        //$codeblock = "<pre><code>$codeblock</code></pre>";
        //$cb = "<pre><code";
        $cb = empty($matches[3]) ? "<pre><code" : "<pre class=\"linenums:$matches[3]\"><code";
        $cb .= empty($matches[2]) ? ">" : " class=\"language-$matches[2]\">";
        $cb .= "$codeblock</code></pre>";
        return "\n\n".$this->hashBlock($cb)."\n\n";
    }

    function doFencedFigures($text)
    {
        $text = preg_replace_callback(
            '{
            (?:\n|\A)
            # 1: Opening marker
            (
                ={3,} # Marker: equal sign.
            )

            [ ]?(?:\[([^\]]+)\])?[ ]* \n # Whitespace and newline following marker.

            # 3: Content
            (
                (?>
                    (?!\1 [ ]?(?:\[([^\]]+)\])?[ ]* \n) # Not a closing marker.
                    .*\n+
                )+
            )

            # Closing marker.
            \1 [ ]?(?:\[([^\]]+)\])?[ ]* \n
        }xm',
            array(&$this, '_doFencedFigures_callback'),
            $text
        );

        return $text;
    }

    function _doFencedFigures_callback($matches)
    {
        # get figcaption
        $topcaption = empty($matches[2]) ? null : $this->runBlockGamut($matches[2]);
        $bottomcaption = empty($matches[5]) ? null : $this->runBlockGamut($matches[5]);
        $figure = $matches[3];
        $figure = $this->runBlockGamut($figure); # recurse

        $figure = preg_replace('/^/m', "  ", $figure);
        # These leading spaces cause problem with <pre> content,
        # so we need to fix that - reuse blockqoute code to handle this:
        $figure = preg_replace_callback(
            '{(\s*<pre>.+?</pre>)}sx',
            array(&$this, '_doBlockQuotes_callback2'),
            $figure
        );

        $res = "<figure>";
        if (! empty($topcaption)) {
            $res .= "\n<figcaption>$topcaption</figcaption>";
        }
        $res .= "\n$figure\n";
        if (! empty($bottomcaption) && empty($topcaption)) {
            $res .= "<figcaption>$bottomcaption</figcaption>";
        }
        $res .= "</figure>";
        return "\n". $this->hashBlock($res)."\n\n";
    }

    function doStrikethroughs($text)
    {
        #
        # Replace ~~some deleted text~~ with <del>some deleted text</del>
        #
        $text = preg_replace_callback(
            '{
                ~~([^~]+)~~
            }xm',
            array(&$this, '_doStrikethroughs_callback'),
            $text
        );
        return $text;
    }

    function _doStrikethroughs_callback($matches)
    {
        $res = "<del>" . $matches[1] . "</del>";
        return $this->hashBlock($res);
    }
}
