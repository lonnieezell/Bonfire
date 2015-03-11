<?php

class CommonMark_LeagueCommonMark
{
    protected $parser = null;

    protected function init()
    {
        if (! $this->loadLibrary()) {
            return false;
        }

        $this->parser = new League\CommonMark\CommonMarkConverter();
    }

    public function parse($text)
    {
        if ($this->parser === null && $this->init() === false) {
            return;
        }

        return $this->parser->convertToHtml($text);
    }

    protected function loadLibrary()
    {
        if (get_instance()->config->item('composer_autoload') !== false) {
            return true;
        }

        $paths = array(
            APPPATH . 'vendor/league/commonmark/src',
            APPPATH . '../vendor/league/commonmark/src',
            APPPATH . 'third_party/league/commonmark/src',
            APPPATH . 'third_party/league/commonmark',
        );
        foreach ($paths as $path) {
            if (file_exists("{$path}/CommonMarkConverter.php")) {
                // This is one of many reasons why autoloaders exist...
                require_once("{$path}/Block/Element/AbstractBlock.php");
                require_once("{$path}/Block/Element/AbstractInlineContainer.php");
                require_once("{$path}/Block/Element/BlockQuote.php");
                require_once("{$path}/Block/Element/Document.php");
                require_once("{$path}/Block/Element/FencedCode.php");
                require_once("{$path}/Block/Element/Header.php");
                require_once("{$path}/Block/Element/HorizontalRule.php");
                require_once("{$path}/Block/Element/HtmlBlock.php");
                require_once("{$path}/Block/Element/IndentedCode.php");
                require_once("{$path}/Block/Element/ListBlock.php");
                require_once("{$path}/Block/Element/ListData.php");
                require_once("{$path}/Block/Element/ListItem.php");
                require_once("{$path}/Block/Element/Paragraph.php");

                require_once("{$path}/Block/Parser/BlockParserInterface.php");
                require_once("{$path}/Block/Parser/AbstractBlockParser.php");
                require_once("{$path}/Block/Parser/ATXHeaderParser.php");
                require_once("{$path}/Block/Parser/BlockQuoteParser.php");
                require_once("{$path}/Block/Parser/FencedCodeParser.php");
                require_once("{$path}/Block/Parser/HorizontalRuleParser.php");
                require_once("{$path}/Block/Parser/HtmlBlockParser.php");
                require_once("{$path}/Block/Parser/IndentedCodeParser.php");
                require_once("{$path}/Block/Parser/LazyParagraphParser.php");
                require_once("{$path}/Block/Parser/ListParser.php");
                require_once("{$path}/Block/Parser/SetExtHeaderParser.php");

                require_once("{$path}/Block/Renderer/BlockRendererInterface.php");
                require_once("{$path}/Block/Renderer/BlockQuoteRenderer.php");
                require_once("{$path}/Block/Renderer/DocumentRenderer.php");
                require_once("{$path}/Block/Renderer/FencedCodeRenderer.php");
                require_once("{$path}/Block/Renderer/HeaderRenderer.php");
                require_once("{$path}/Block/Renderer/HorizontalRuleRenderer.php");
                require_once("{$path}/Block/Renderer/HtmlBlockRenderer.php");
                require_once("{$path}/Block/Renderer/IndentedCodeRenderer.php");
                require_once("{$path}/Block/Renderer/ListBlockRenderer.php");
                require_once("{$path}/Block/Renderer/ListItemRenderer.php");
                require_once("{$path}/Block/Renderer/ParagraphRenderer.php");

                require_once("{$path}/ContextInterface.php");
                require_once("{$path}/Context.php");

                require_once("{$path}/Cursor.php");
                require_once("{$path}/CursorState.php");

                require_once("{$path}/Delimiter/Delimiter.php");
                require_once("{$path}/Delimiter/DelimiterStack.php");

                require_once("{$path}/DocParser.php");
                require_once("{$path}/Environment.php");
                require_once("{$path}/EnvironmentAwareInterface.php");

                require_once("{$path}/Extension/ExtensionInterface.php");
                require_once("{$path}/Extension/Extension.php");
                require_once("{$path}/Extension/CommonMarkCoreExtension.php");
                require_once("{$path}/Extension/MiscExtension.php");

                require_once("{$path}/HtmlElement.php");

                require_once("{$path}/HtmlRendererInterface.php");
                require_once("{$path}/HtmlRenderer.php");

                require_once("{$path}/Inline/Element/AbstractInline.php");
                require_once("{$path}/Inline/Element/AbstractInlineContainer.php");
                require_once("{$path}/Inline/Element/AbstractStringContainer.php");
                require_once("{$path}/Inline/Element/AbstractWebResource.php");
                require_once("{$path}/Inline/Element/Code.php");
                require_once("{$path}/Inline/Element/Emphasis.php");
                require_once("{$path}/Inline/Element/Html.php");
                require_once("{$path}/Inline/Element/Image.php");
                require_once("{$path}/Inline/Element/Link.php");
                require_once("{$path}/Inline/Element/Newline.php");
                require_once("{$path}/Inline/Element/Strong.php");
                require_once("{$path}/Inline/Element/Text.php");

                require_once("{$path}/Inline/Parser/InlineParserInterface.php");
                require_once("{$path}/Inline/Parser/AbstractInlineParser.php");
                require_once("{$path}/Inline/Parser/AutolinkParser.php");
                require_once("{$path}/Inline/Parser/BacktickParser.php");
                require_once("{$path}/Inline/Parser/BangParser.php");
                require_once("{$path}/Inline/Parser/CloseBracketParser.php");
                require_once("{$path}/Inline/Parser/EmphasisParser.php");
                require_once("{$path}/Inline/Parser/EntityParser.php");
                require_once("{$path}/Inline/Parser/EscapableParser.php");
                require_once("{$path}/Inline/Parser/NewlineParser.php");
                require_once("{$path}/Inline/Parser/OpenBracketParser.php");
                require_once("{$path}/Inline/Parser/RawHtmlParser.php");

                require_once("{$path}/Inline/Processor/InlineProcessorInterface.php");
                require_once("{$path}/Inline/Processor/EmphasisProcessor.php");

                require_once("{$path}/Inline/Renderer/InlineRendererInterface.php");
                require_once("{$path}/Inline/Renderer/CodeRenderer.php");
                require_once("{$path}/Inline/Renderer/EmphasisRenderer.php");
                require_once("{$path}/Inline/Renderer/ImageRenderer.php");
                require_once("{$path}/Inline/Renderer/LinkRenderer.php");
                require_once("{$path}/Inline/Renderer/NewlineRenderer.php");
                require_once("{$path}/Inline/Renderer/RawHtmlRenderer.php");
                require_once("{$path}/Inline/Renderer/StrongRenderer.php");
                require_once("{$path}/Inline/Renderer/TextRenderer.php");

                require_once("{$path}/InlineParserContext.php");
                require_once("{$path}/InlineParserEngine.php");

                require_once("{$path}/Reference/Reference.php");
                require_once("{$path}/Reference/ReferenceMap.php");

                require_once("{$path}/ReferenceParser.php");
                require_once("{$path}/UnmatchedBlockCloser.php");

                require_once("{$path}/Util/ArrayCollection.php");
                require_once("{$path}/Util/Html5Entities.php");
                require_once("{$path}/Util/LinkParserHelper.php");
                require_once("{$path}/Util/RegexHelper.php");
                require_once("{$path}/Util/TextHelper.php");
                require_once("{$path}/Util/UrlEncoder.php");

                require_once("{$path}/CommonMarkConverter.php");

                return true;
            }
        }

        log_message('error', 'CommonMark_LeagueCommonMark: Could not find League\CommonMark');

        return false;
    }
}
