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
 * CommonMark driver for league/commonmark v0.7.2
 *
 * Adapter to use the league/commonmark library within the Bonfire CommonMark library.
 *
 * @package Bonfire\Libraries\CommonMark\Drivers\CommonMark_Markdown
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/developer/commonmark
 * @link    https://github.com/thephpleague/commonmark
 */
class CommonMark_LeagueCommonMark extends CommonMarkDriver
{
    /** @var string The class to instantiate and load into $this->converter. */
    protected $converterLib = 'League\CommonMark\CommonMarkConverter';

    /** @var array The files required to load the library manually. */
    protected $files = array(
        'Block/Element/AbstractBlock.php',
        'Block/Element/AbstractInlineContainer.php',
        'Block/Element/BlockQuote.php',
        'Block/Element/Document.php',
        'Block/Element/FencedCode.php',
        'Block/Element/Header.php',
        'Block/Element/HorizontalRule.php',
        'Block/Element/HtmlBlock.php',
        'Block/Element/IndentedCode.php',
        'Block/Element/ListBlock.php',
        'Block/Element/ListData.php',
        'Block/Element/ListItem.php',
        'Block/Element/Paragraph.php',
        'Block/Parser/BlockParserInterface.php',
        'Block/Parser/AbstractBlockParser.php',
        'Block/Parser/ATXHeaderParser.php',
        'Block/Parser/BlockQuoteParser.php',
        'Block/Parser/FencedCodeParser.php',
        'Block/Parser/HorizontalRuleParser.php',
        'Block/Parser/HtmlBlockParser.php',
        'Block/Parser/IndentedCodeParser.php',
        'Block/Parser/LazyParagraphParser.php',
        'Block/Parser/ListParser.php',
        'Block/Parser/SetExtHeaderParser.php',
        'Block/Renderer/BlockRendererInterface.php',
        'Block/Renderer/BlockQuoteRenderer.php',
        'Block/Renderer/DocumentRenderer.php',
        'Block/Renderer/FencedCodeRenderer.php',
        'Block/Renderer/HeaderRenderer.php',
        'Block/Renderer/HorizontalRuleRenderer.php',
        'Block/Renderer/HtmlBlockRenderer.php',
        'Block/Renderer/IndentedCodeRenderer.php',
        'Block/Renderer/ListBlockRenderer.php',
        'Block/Renderer/ListItemRenderer.php',
        'Block/Renderer/ParagraphRenderer.php',
        'ContextInterface.php',
        'Context.php',
        'Cursor.php',
        'CursorState.php',
        'Delimiter/Delimiter.php',
        'Delimiter/DelimiterStack.php',
        'DocParser.php',
        'Environment.php',
        'EnvironmentAwareInterface.php',
        'Extension/ExtensionInterface.php',
        'Extension/Extension.php',
        'Extension/CommonMarkCoreExtension.php',
        'Extension/MiscExtension.php',
        'HtmlElement.php',
        'HtmlRendererInterface.php',
        'HtmlRenderer.php',
        'Inline/Element/AbstractInline.php',
        'Inline/Element/AbstractInlineContainer.php',
        'Inline/Element/AbstractStringContainer.php',
        'Inline/Element/AbstractWebResource.php',
        'Inline/Element/Code.php',
        'Inline/Element/Emphasis.php',
        'Inline/Element/Html.php',
        'Inline/Element/Image.php',
        'Inline/Element/Link.php',
        'Inline/Element/Newline.php',
        'Inline/Element/Strong.php',
        'Inline/Element/Text.php',
        'Inline/Parser/InlineParserInterface.php',
        'Inline/Parser/AbstractInlineParser.php',
        'Inline/Parser/AutolinkParser.php',
        'Inline/Parser/BacktickParser.php',
        'Inline/Parser/BangParser.php',
        'Inline/Parser/CloseBracketParser.php',
        'Inline/Parser/EmphasisParser.php',
        'Inline/Parser/EntityParser.php',
        'Inline/Parser/EscapableParser.php',
        'Inline/Parser/NewlineParser.php',
        'Inline/Parser/OpenBracketParser.php',
        'Inline/Parser/RawHtmlParser.php',
        'Inline/Processor/InlineProcessorInterface.php',
        'Inline/Processor/EmphasisProcessor.php',
        'Inline/Renderer/InlineRendererInterface.php',
        'Inline/Renderer/CodeRenderer.php',
        'Inline/Renderer/EmphasisRenderer.php',
        'Inline/Renderer/ImageRenderer.php',
        'Inline/Renderer/LinkRenderer.php',
        'Inline/Renderer/NewlineRenderer.php',
        'Inline/Renderer/RawHtmlRenderer.php',
        'Inline/Renderer/StrongRenderer.php',
        'Inline/Renderer/TextRenderer.php',
        'InlineParserContext.php',
        'InlineParserEngine.php',
        'Reference/Reference.php',
        'Reference/ReferenceMap.php',
        'ReferenceParser.php',
        'UnmatchedBlockCloser.php',
        'Util/ArrayCollection.php',
        'Util/Html5Entities.php',
        'Util/LinkParserHelper.php',
        'Util/RegexHelper.php',
        'Util/TextHelper.php',
        'Util/UrlEncoder.php',
        'CommonMarkConverter.php',
    );

    /**
     * Set the paths array, in case the library must be loaded manually.
     */
    public function __construct()
    {
        $this->paths = array(
            APPPATH . 'vendor/league/commonmark/src',
            APPPATH . '../vendor/league/commonmark/src',
            APPPATH . 'third_party/league/commonmark/src',
            APPPATH . 'third_party/league/commonmark',
        );
    }

    /**
     * The library method used to convert CommonMark to HTML.
     *
     * @param string $text CommonMark text to convert.
     *
     * @return string HTML text.
     */
    protected function toHtml($text)
    {
        return $this->converter->convertToHtml($text);
    }
}
