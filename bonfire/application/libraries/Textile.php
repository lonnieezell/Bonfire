<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Example: get XHTML from a given Textile-markup string ($string)
 *
 *		  $textile = new Textile;
 *		  echo $textile->TextileThis($string);
 *
 */

/*
$HeadURL: https://textpattern.googlecode.com/svn/releases/4.3.0/source/textpattern/lib/classTextile.php $
$LastChangedRevision: 3438 $
*/

/*

_____________
T E X T I L E

A Humane Web Text Generator

Version 2.2

Copyright (c) 2003-2004, Dean Allen <dean@textism.com>
All rights reserved.

Thanks to Carlo Zottmann <carlo@g-blog.net> for refactoring
Textile's procedural code into a class framework

Additions and fixes Copyright (c) 2006 Alex Shiels http://thresholdstate.com/

_____________
L I C E N S E

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice,
  this list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
  and/or other materials provided with the distribution.

* Neither the name Textile nor the names of its contributors may be used to
  endorse or promote products derived from this software without specific
  prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
POSSIBILITY OF SUCH DAMAGE.

_________
U S A G E

Block modifier syntax:

	Header: h(1-6).
	Paragraphs beginning with 'hn. ' (where n is 1-6) are wrapped in header tags.
	Example: h1. Header... -> <h1>Header...</h1>

	Paragraph: p. (also applied by default)
	Example: p. Text -> <p>Text</p>

	Blockquote: bq.
	Example: bq. Block quotation... -> <blockquote>Block quotation...</blockquote>

	Blockquote with citation: bq.:http://citation.url
	Example: bq.:http://textism.com/ Text...
	->	<blockquote cite="http://textism.com">Text...</blockquote>

	Footnote: fn(1-100).
	Example: fn1. Footnote... -> <p id="fn1">Footnote...</p>

	Numeric list: #, ##
	Consecutive paragraphs beginning with # are wrapped in ordered list tags.
	Example: <ol><li>ordered list</li></ol>

	Bulleted list: *, **
	Consecutive paragraphs beginning with * are wrapped in unordered list tags.
	Example: <ul><li>unordered list</li></ul>

	Definition list:
		Terms ;, ;;
		Definitions :, ::
	Consecutive paragraphs beginning with ; or : are wrapped in definition list tags.
	Example: <dl><dt>term</dt><dd>definition</dd></dl>

Phrase modifier syntax:

		   _emphasis_	->	 <em>emphasis</em>
		   __italic__	->	 <i>italic</i>
			 *strong*	->	 <strong>strong</strong>
			 **bold**	->	 <b>bold</b>
		 ??citation??	->	 <cite>citation</cite>
	   -deleted text-	->	 <del>deleted</del>
	  +inserted text+	->	 <ins>inserted</ins>
		^superscript^	->	 <sup>superscript</sup>
		  ~subscript~	->	 <sub>subscript</sub>
			   @code@	->	 <code>computer code</code>
		  %(bob)span%	->	 <span class="bob">span</span>

		==notextile==	->	 leave text alone (do not format)

	   "linktext":url	->	 <a href="url">linktext</a>
 "linktext(title)":url	->	 <a href="url" title="title">linktext</a>
            "$":url  ->  <a href="url">url</a>
     "$(title)":url  ->  <a href="url" title="title">url</a>

		   !imageurl!	->	 <img src="imageurl" />
	!imageurl(alt text)!	->	 <img src="imageurl" alt="alt text" />
	!imageurl!:linkurl	->	 <a href="linkurl"><img src="imageurl" /></a>

ABC(Always Be Closing)	->	 <acronym title="Always Be Closing">ABC</acronym>


Linked Notes:
============

	Allows the generation of an automated list of notes with links.

	Linked notes are composed of three parts, a set of named _definitions_, a set of
	_references_ to those definitions and one or more _placeholders_ indicating where
	the consolidated list of notes is to be placed in your document.

	Definitions.
	-----------

	Each note definition must occur in its own paragraph and should look like this...

	note#mynotelabel. Your definition text here.

	You are free to use whatever label you wish after the # as long as it is made up
	of letters, numbers, colon(:) or dash(-).

	References.
	----------

	Each note reference is marked in your text like this[#mynotelabel] and
	it will be replaced with a superscript reference that links into the list of
	note definitions.

	List Placeholder(s).
	-------------------

	The note list can go anywhere in your document. You have to indicate where
	like this...

	notelist.

	notelist can take attributes (class#id) like this: notelist(class#id).

	By default, the note list will show each definition in the order that they
	are referenced in the text by the _references_. It will show each definition with
	a full list of backlinks to each reference. If you do not want this, you can choose
	to override the backlinks like this...

	notelist(class#id)!.    Produces a list with no backlinks.
	notelist(class#id)^.    Produces a list with only the first backlink.

	Should you wish to have a specific definition display backlinks differently to this
	then you can override the backlink method by appending a link override to the
	_definition_ you wish to customise.

	note#label.    Uses the citelist's setting for backlinks.
	note#label!.   Causes that definition to have no backlinks.
	note#label^.   Causes that definition to have one backlink (to the first ref.)
	note#label*.   Causes that definition to have all backlinks.

	Any unreferenced notes will be left out of the list unless you explicitly state
	you want them by adding a '+'. Like this...

	notelist(class#id)!+. Giving a list of all notes without any backlinks.

	You can mix and match the list backlink control and unreferenced links controls
	but the backlink control (if any) must go first. Like so: notelist^+. , not
	like this: notelist+^.

	Example...
		Scientists say[#lavader] the moon is small.

		note#other. An unreferenced note.

		note#lavader(myliclass). "Proof":url of a small moon.

		notelist(myclass#myid)+.

		Would output (the actual IDs used would be randomised)...

		<p>Scientists say<sup><a href="#def_id_1" id="ref_id_1a">1</sup> the moon is small.</p>

		<ol class="myclass" id="myid">
			<li class="myliclass"><a href="#ref_id_1a"><sup>a</sup></a><span id="def_id_1"> </span><a href="url">Proof</a> of a small moon.</li>
			<li>An unreferenced note.</li>
		</ol>

		The 'a b c' backlink characters can be altered too.
		For example if you wanted the notes to have numeric backlinks starting from 1:

		notelist:1.

Table syntax:

	Simple tables:

		|a|simple|table|row|
		|And|Another|table|row|
		|With an||empty|cell|

		|=. My table caption goes here
		|_. A|_. table|_. header|_.row|
		|A|simple|table|row|

	Tables with attributes:

		table{border:1px solid black}. My table summary here
		{background:#ddd;color:red}. |{}| | | |

	To specify thead / tfoot / tbody groups, add one of these on its own line
	above the row(s) you wish to wrap (you may specify attributes before the dot):

		|^.     # thead
		|-.     # tbody
		|~.     # tfoot

	Column groups:

		|:\3. 100

		Becomes:
			<colgroup span="3" width="100"></colgroup>

		You can omit either of the \N or width values. You may also
		add cells after the colgroup definition to specify span/width/attributes:

		|:\5. 50 |(firstcol). |\2. 250||300|

		Becomes:
			<colgroup span="5" width="50">
				<col class="firstcol" />
				<col span="2" width="250" />
				<col />
				<col width="300" />
			</colgroup>

Applying Attributes:

	Most anywhere Textile code is used, attributes such as arbitrary css style,
	css classes, and ids can be applied. The syntax is fairly consistent.

	The following characters quickly alter the alignment of block elements:

		<  ->  left align	 ex. p<. left-aligned para
		>  ->  right align		 h3>. right-aligned header 3
		=  ->  centred			 h4=. centred header 4
		<> ->  justified		 p<>. justified paragraph

	These will change vertical alignment in table cells:

		^  ->  top		   ex. |^. top-aligned table cell|
		-  ->  middle		   |-. middle aligned|
		~  ->  bottom		   |~. bottom aligned cell|

	Plain (parentheses) inserted between block syntax and the closing dot-space
	indicate classes and ids:

		p(hector). paragraph -> <p class="hector">paragraph</p>

		p(#fluid). paragraph -> <p id="fluid">paragraph</p>

		(classes and ids can be combined)
		p(hector#fluid). paragraph -> <p class="hector" id="fluid">paragraph</p>

	Curly {brackets} insert arbitrary css style

		p{line-height:18px}. paragraph -> <p style="line-height:18px">paragraph</p>

		h3{color:red}. header 3 -> <h3 style="color:red">header 3</h3>

	Square [brackets] insert language attributes

		p[no]. paragraph -> <p lang="no">paragraph</p>

		%[fr]phrase% -> <span lang="fr">phrase</span>

	Usually Textile block element syntax requires a dot and space before the block
	begins, but since lists don't, they can be styled just using braces

		#{color:blue} one  ->  <ol style="color:blue">
		# big					<li>one</li>
		# list					<li>big</li>
								<li>list</li>
							   </ol>

	Using the span tag to style a phrase

		It goes like this, %{color:red}the fourth the fifth%
			  -> It goes like this, <span style="color:red">the fourth the fifth</span>

*/

// define these before including this file to override the standard glyphs
@define('txt_quote_single_open',  '&#8216;');
@define('txt_quote_single_close', '&#8217;');
@define('txt_quote_double_open',  '&#8220;');
@define('txt_quote_double_close', '&#8221;');
@define('txt_apostrophe',         '&#8217;');
@define('txt_prime',              '&#8242;');
@define('txt_prime_double',       '&#8243;');
@define('txt_ellipsis',           '&#8230;');
@define('txt_emdash',             '&#8212;');
@define('txt_endash',             '&#8211;');
@define('txt_dimension',          '&#215;');
@define('txt_trademark',          '&#8482;');
@define('txt_registered',         '&#174;');
@define('txt_copyright',          '&#169;');
@define('txt_half',               '&#189;');
@define('txt_quarter',            '&#188;');
@define('txt_threequarters',      '&#190;');
@define('txt_degrees',            '&#176;');
@define('txt_plusminus',          '&#177;');
@define('txt_has_unicode',        @preg_match('/\pL/u', 'a')); // Detect if Unicode is compiled into PCRE

class Textile
{
	var $hlgn;
	var $vlgn;
	var $clas;
	var $lnge;
	var $styl;
	var $cspn;
	var $rspn;
	var $a;
	var $s;
	var $c;
	var $pnct;
	var $rel;
	var $fn;

	var $shelf = array();
	var $restricted = false;
	var $noimage = false;
	var $lite = false;
	var $url_schemes = array();
	var $glyph = array();
	var $hu = '';
	var $max_span_depth = 5;

	var $ver = '2.2.0';
	var $rev = '$Rev: 3438 $';

	var $doc_root;

// -------------------------------------------------------------
	function Textile()
	{
		$this->hlgn = "(?:\<(?!>)|(?<!<)\>|\<\>|\=|[()]+(?! ))";
		$this->vlgn = "[\-^~]";
		$this->clas = "(?:\([^)\n]+\))";	# Don't allow classes/ids/languages/styles to span across newlines
		$this->lnge = "(?:\[[^]\n]+\])";
		$this->styl = "(?:\{[^}\n]+\})";
		$this->cspn = "(?:\\\\\d+)";
		$this->rspn = "(?:\/\d+)";
		$this->a  = "(?:{$this->hlgn}|{$this->vlgn})*";
		$this->s  = "(?:{$this->cspn}|{$this->rspn})*";
		$this->c  = "(?:{$this->clas}|{$this->styl}|{$this->lnge}|{$this->hlgn})*";
		$this->lc = "(?:{$this->clas}|{$this->styl}|{$this->lnge})*";

		$this->pnct  = '[\!"#\$%&\'()\*\+,\-\./:;<=>\?@\[\\\]\^_`{\|}\~]';
		$this->urlch = '[\w"$\-_.+!*\'(),";\/?:@=&%#{}|\\^~\[\]`]';
		$pnc = '[[:punct:]]';

		$this->url_schemes = array('http','https','ftp','mailto');

		$this->btag = array('bq', 'bc', 'notextile', 'pre', 'h[1-6]', 'fn\d+', 'p', '###' );

		if (txt_has_unicode) {
			$this->regex_snippets = array(
				'acr' => '\p{Lu}\p{Nd}',
				'abr' => '\p{Lu}',
				'nab' => '\p{Ll}',
				'wrd' => '(?:\p{L}|\p{M}|\p{N}|\p{Pc})',
				'mod' => 'u', # Make sure to mark the unicode patterns as such, Some servers seem to need this.
			);
		} else {
			$this->regex_snippets = array(
				'acr' => 'A-Z0-9',
				'abr' => 'A-Z',
				'nab' => 'a-z',
				'wrd' => '\w',
				'mod' => '',
			);
		}
		extract( $this->regex_snippets );

		$this->glyph_search = array(
			'/('.$wrd.')\'('.$wrd.')/'.$mod,        // I'm an apostrophe
			'/(\s)\'(\d+'.$wrd.'?)\b(?![.]?['.$wrd.']*?\')/'.$mod,	// back in '88/the '90s but not in his '90s', '1', '1.' '10m' or '5.png'
			'/(\S)\'(?=\s|'.$pnc.'|<|$)/',          // single closing
			'/\'/',                                 // single opening
			'/(\S)\"(?=\s|'.$pnc.'|<|$)/',          // double closing
			'/"/',                                  // double opening
			'/\b(['.$abr.']['.$acr.']{2,})\b(?:[(]([^)]*)[)])/'.$mod,  // 3+ uppercase acronym
			'/(?<=\s|^|[>(;-])(['.$abr.']{3,})(['.$nab.']*)(?=\s|'.$pnc.'|<|$)(?=[^">]*?(<|$))/'.$mod,  // 3+ uppercase
			'/([^.]?)\.{3}/',                       // ellipsis
			'/(\s?)--(\s?)/',                       // em dash
			'/\s-(?:\s|$)/',                        // en dash
			'/(\d+)( ?)x( ?)(?=\d+)/',              // dimension sign
			'/(\b ?|\s|^)[([]TM[])]/i',             // trademark
			'/(\b ?|\s|^)[([]R[])]/i',              // registered
			'/(\b ?|\s|^)[([]C[])]/i',              // copyright
			'/[([]1\/4[])]/',                       // 1/4
			'/[([]1\/2[])]/',                       // 1/2
			'/[([]3\/4[])]/',                       // 3/4
			'/[([]o[])]/',                          // degrees -- that's a small 'oh'
			'/[([]\+\/-[])]/',                      // plus minus
		);

		$this->glyph_replace = array(
			'$1'.txt_apostrophe.'$2',              // I'm an apostrophe
			'$1'.txt_apostrophe.'$2',              // back in '88
			'$1'.txt_quote_single_close,           // single closing
			txt_quote_single_open,                 // single opening
			'$1'.txt_quote_double_close,           // double closing
			txt_quote_double_open,                 // double opening
			'<acronym title="$2">$1</acronym>',     // 3+ uppercase acronym
			'<span class="caps">glyph:$1</span>$2', // 3+ uppercase
			'$1'.txt_ellipsis,                     // ellipsis
			'$1'.txt_emdash.'$2',                  // em dash
			' '.txt_endash.' ',                    // en dash
			'$1$2'.txt_dimension.'$3',             // dimension sign
			'$1'.txt_trademark,                    // trademark
			'$1'.txt_registered,                   // registered
			'$1'.txt_copyright,                    // copyright
			txt_quarter,                           // 1/4
			txt_half,                              // 1/2
			txt_threequarters,                     // 3/4
			txt_degrees,                           // degrees
			txt_plusminus,                         // plus minus
		);

		if (defined('hu'))
			$this->hu = hu;

		if (defined('DIRECTORY_SEPARATOR'))
			$this->ds = constant('DIRECTORY_SEPARATOR');
		else
			$this->ds = '/';

		$this->doc_root = @$_SERVER['DOCUMENT_ROOT'];
		if (!$this->doc_root)
			$this->doc_root = @$_SERVER['PATH_TRANSLATED']; // IIS

		$this->doc_root = rtrim($this->doc_root, $this->ds).$this->ds;
	}

// -------------------------------------------------------------

	function TextileThis($text, $lite = '', $encode = '', $noimage = '', $strict = '', $rel = '')
	{
		$this->span_depth = 0;
		$this->tag_index = 1;
		$this->notes = $this->unreferencedNotes = $this->notelist_cache = array();
		$this->note_index = 1;
		$this->rel = ($rel) ? ' rel="'.$rel.'"' : '';

		$this->lite = $lite;
		$this->noimage = $noimage;

		if ($encode)
		{
			$text = $this->incomingEntities($text);
			$text = str_replace("x%x%", "&amp;", $text);
			return $text;
		} else {
			if(!$strict) {
				$text = $this->cleanWhiteSpace($text);
			}

			if(!$lite) {
				$text = $this->block($text);
				$text = $this->placeNoteLists($text);
			}

			$text = $this->retrieve($text);
			$text = $this->replaceGlyphs($text);
			$text = $this->retrieveTags($text);
			$text = $this->retrieveURLs($text);
			$this->span_depth = 0;

			// just to be tidy
			$text = str_replace("<br />", "<br />\n", $text);

			return $text;
		}
	}

// -------------------------------------------------------------

	function TextileRestricted($text, $lite = 1, $noimage = 1, $rel = 'nofollow')
	{
		$this->restricted = true;
		$this->lite = $lite;
		$this->noimage = $noimage;

		$this->span_depth = 0;
		$this->tag_index = 1;
		$this->notes = $this->unreferencedNotes = $this->notelist_cache = array();
		$this->note_index = 1;

		$this->rel = ($rel) ? ' rel="'.$rel.'"' : '';

		// escape any raw html
		$text = $this->encode_html($text, 0);

		$text = $this->cleanWhiteSpace($text);

		if($lite) {
			$text = $this->blockLite($text);
		} else {
			$text = $this->block($text);
			$text = $this->placeNoteLists($text);
		}

		$text = $this->retrieve($text);
		$text = $this->replaceGlyphs($text);
		$text = $this->retrieveTags($text);
		$text = $this->retrieveURLs($text);
		$this->span_depth = 0;

		// just to be tidy
		$text = str_replace("<br />", "<br />\n", $text);

		return $text;
	}

// -------------------------------------------------------------
	function pba($in, $element = "", $include_id = 1) // "parse block attributes"
	{
		$style = '';
		$class = '';
		$lang = '';
		$colspan = '';
		$rowspan = '';
		$span = '';
		$width = '';
		$id = '';
		$atts = '';

		if (!empty($in)) {
			$matched = $in;
			if ($element == 'td') {
				if (preg_match("/\\\\(\d+)/", $matched, $csp)) $colspan = $csp[1];
				if (preg_match("/\/(\d+)/", $matched, $rsp)) $rowspan = $rsp[1];
			}

			if ($element == 'td' or $element == 'tr') {
				if (preg_match("/($this->vlgn)/", $matched, $vert))
					$style[] = "vertical-align:" . $this->vAlign($vert[1]);
			}

			if (preg_match("/\{([^}]*)\}/", $matched, $sty)) {
				$style[] = rtrim($sty[1], ';');
				$matched = str_replace($sty[0], '', $matched);
			}

			if (preg_match("/\[([^]]+)\]/U", $matched, $lng)) {
				$lang = $lng[1];
				$matched = str_replace($lng[0], '', $matched);
			}

			if (preg_match("/\(([^()]+)\)/U", $matched, $cls)) {
				$class = $cls[1];
				$matched = str_replace($cls[0], '', $matched);
			}

			if (preg_match("/([(]+)/", $matched, $pl)) {
				$style[] = "padding-left:" . strlen($pl[1]) . "em";
				$matched = str_replace($pl[0], '', $matched);
			}

			if (preg_match("/([)]+)/", $matched, $pr)) {
				$style[] = "padding-right:" . strlen($pr[1]) . "em";
				$matched = str_replace($pr[0], '', $matched);
			}

			if (preg_match("/($this->hlgn)/", $matched, $horiz))
				$style[] = "text-align:" . $this->hAlign($horiz[1]);

			if (preg_match("/^(.*)#(.*)$/", $class, $ids)) {
				$id = $ids[2];
				$class = $ids[1];
			}

			if ($element == 'col') {
				if (preg_match("/(?:\\\\(\d+))?\s*(\d+)?/", $matched, $csp)) {
					$span = isset($csp[1]) ? $csp[1] : '';
					$width = isset($csp[2]) ? $csp[2] : '';
				}
			}

			if ($this->restricted)
				return ($lang)	  ? ' lang="'	. $lang . '"':'';

			$o = '';
			if( $style ) {
				foreach($style as $s) {
					$parts = split(';', $s);
					foreach( $parts as $p ) {
						$p = trim($p, '; ');
						if( !empty( $p ) )
							$o .= $p.'; ';
					}
				}
				$style = trim( strtr($o, array("\n"=>'',';;'=>';')) );
			}

			return join('',array(
				($style)   ? ' style="'   . $style    .'"':'',
				($class)   ? ' class="'   . $class    .'"':'',
				($lang)    ? ' lang="'    . $lang     .'"':'',
				($id and $include_id) ? ' id="' . $id .'"':'',
				($colspan) ? ' colspan="' . $colspan  .'"':'',
				($rowspan) ? ' rowspan="' . $rowspan  .'"':'',
				($span)    ? ' span="'    . $span     .'"':'',
				($width)   ? ' width="'   . $width    .'"':'',
			));
		}
		return '';
	}

// -------------------------------------------------------------
	function hasRawText($text)
	{
		// checks whether the text has text not already enclosed by a block tag
		$r = trim(preg_replace('@<(p|blockquote|div|form|table|ul|ol|dl|pre|h\d)[^>]*?>.*</\1>@s', '', trim($text)));
		$r = trim(preg_replace('@<(hr|br)[^>]*?/>@', '', $r));
		return '' != $r;
	}

// -------------------------------------------------------------
	function table($text)
	{
		$text = $text . "\n\n";
		return preg_replace_callback("/^(?:table(_?{$this->s}{$this->a}{$this->c})\.(.*)?\n)?^({$this->a}{$this->c}\.? ?\|.*\|)[\s]*\n\n/smU",
			 array(&$this, "fTable"), $text);
	}

// -------------------------------------------------------------
	function fTable($matches)
	{
		$tatts = $this->pba($matches[1], 'table');

		$sum = trim($matches[2]) ? ' summary="'.htmlspecialchars(trim($matches[2])).'"' : '';
		$cap = '';
		$colgrp = $last_rgrp = '';
		foreach(preg_split("/\|\s*?$/m", $matches[3], -1, PREG_SPLIT_NO_EMPTY) as $row) {
			// Caption
			if (preg_match("/^\|\=($this->s$this->a$this->c)\. ([^\|\n]*)(.*)/s", ltrim($row), $cmtch)) {
				$capts = $this->pba($cmtch[1]);
				$cap = "\t<caption".$capts.">".trim($cmtch[2])."</caption>\n";
				$row = $cmtch[3];
			}

			// Colgroup
			if (preg_match("/^\|:($this->s$this->a$this->c\. .*)/m", ltrim($row), $gmtch)) {
				$idx=0;
				foreach (explode('|', str_replace('.', '', $gmtch[1])) as $col) {
					$gatts = $this->pba(trim($col), 'col');
					$colgrp .= "\t<col".(($idx==0) ? "group".$gatts.">" : $gatts." />")."\n";
					$idx++;
				}
				$colgrp .= "\t</colgroup>\n";
				continue;
			}

			preg_match("/(:?^\|($this->vlgn)($this->s$this->a$this->c)\.\s*$\n)?^(.*)/sm", ltrim($row), $grpmatch);

			// Row group
			$rgrp = isset($grpmatch[2]) ? (($grpmatch[2] == '^') ? 'head' : ( ($grpmatch[2] == '~') ? 'foot' : (($grpmatch[2] == '-') ? 'body' : '' ) ) ) : '';
			$rgrpatts = isset($grpmatch[3]) ? $this->pba($grpmatch[3]) : '';
			$row = $grpmatch[4];

			if (preg_match("/^($this->a$this->c\. )(.*)/m", ltrim($row), $rmtch)) {
				$ratts = $this->pba($rmtch[1], 'tr');
				$row = $rmtch[2];
			} else $ratts = '';

			$cells = array();
			$cellctr = 0;
			foreach(explode("|", $row) as $cell) {
				$ctyp = "d";
				if (preg_match("/^_/", $cell)) $ctyp = "h";
				if (preg_match("/^(_?$this->s$this->a$this->c\. )(.*)/", $cell, $cmtch)) {
					$catts = $this->pba($cmtch[1], 'td');
					$cell = $cmtch[2];
				} else $catts = '';

				$cell = $this->graf($cell);

				if ($cellctr>0) // Ignore first 'cell': it precedes the opening pipe
					$cells[] = $this->doTagBr("t$ctyp", "\t\t\t<t$ctyp$catts>$cell</t$ctyp>");

				$cellctr++;
			}
			$grp = (($rgrp && $last_rgrp) ? "\t</t".$last_rgrp.">\n" : '') . (($rgrp) ? "\t<t".$rgrp.$rgrpatts.">\n" : '');
			$last_rgrp = ($rgrp) ? $rgrp : $last_rgrp;
			$rows[] = $grp."\t\t<tr$ratts>\n" . join("\n", $cells) . ($cells ? "\n" : "") . "\t\t</tr>";
			unset($cells, $catts);
		}

		return "\t<table{$tatts}{$sum}>\n" .$cap. $colgrp. join("\n", $rows) . "\n".(($last_rgrp) ? "\t</t".$last_rgrp.">\n" : '')."\t</table>\n\n";
	}

// -------------------------------------------------------------
	function lists($text)
	{
		return preg_replace_callback("/^([#*;:]+$this->lc[ .].*)$(?![^#*;:])/smU", array(&$this, "fList"), $text);
	}

// -------------------------------------------------------------
	function fList($m)
	{
		$text = preg_split('/\n(?=[*#;:])/m', $m[0]);
		$pt = '';
		foreach($text as $nr => $line) {
			$nextline = isset($text[$nr+1]) ? $text[$nr+1] : false;
			if (preg_match("/^([#*;:]+)($this->lc)[ .](.*)$/s", $line, $m)) {
				list(, $tl, $atts, $content) = $m;
				$content = trim($content);
				$nl = '';
				$ltype = $this->lT($tl);
				$litem = (strpos($tl, ';') !== false) ? 'dt' : ((strpos($tl, ':') !== false) ? 'dd' : 'li');
				$showitem = (strlen($content) > 0);

				if (preg_match("/^([#*;:]+)($this->lc)[ .].*/", $nextline, $nm))
					$nl = $nm[1];

				if ((strpos($pt, ';') !== false) && (strpos($tl, ':') !== false)) {
					$lists[$tl] = 2; // We're already in a <dl> so flag not to start another
				}

				$atts = $this->pba($atts);
				if (!isset($lists[$tl])) {
					$lists[$tl] = 1;
					$line = "\t<" . $ltype . "l$atts>" . (($showitem) ? "\n\t\t<$litem>" . $content : '');
				} else {
					$line = ($showitem) ? "\t\t<$litem$atts>" . $content : '';
				}

				if((strlen($nl) <= strlen($tl))) $line .= (($showitem) ? "</$litem>" : '');
				foreach(array_reverse($lists) as $k => $v) {
					if(strlen($k) > strlen($nl)) {
						$line .= ($v==2) ? '' : "\n\t</" . $this->lT($k) . "l>";
						if((strlen($k) > 1) && ($v != 2))
							$line .= "</".$litem.">";
						unset($lists[$k]);
					}
				}
				$pt = $tl; // Remember the current Textile tag
			}
			else {
				$line .= "\n";
			}
			$out[] = $line;
		}
		return $this->doTagBr($litem, join("\n", $out));
	}

// -------------------------------------------------------------
	function lT($in)
	{
		return preg_match("/^#+/", $in) ? 'o' : ((preg_match("/^\*+/", $in)) ? 'u' : 'd');
	}

// -------------------------------------------------------------
	function doTagBr($tag, $in)
	{
		return preg_replace_callback('@<('.preg_quote($tag).')([^>]*?)>(.*)(</\1>)@s', array(&$this, 'fBr'), $in);
	}

// -------------------------------------------------------------
	function doPBr($in)
	{
		return preg_replace_callback('@<(p)([^>]*?)>(.*)(</\1>)@s', array(&$this, 'fPBr'), $in);
	}

// -------------------------------------------------------------
	function fPBr($m)
	{
		# Less restrictive version of fBr() ... used only in paragraphs where the next
		# row may start with a smiley or perhaps something like '#8 bolt...' or '*** stars...'
		$content = preg_replace("@(.+)(?<!<br>|<br />)\n(?![\s|])@", '$1<br />', $m[3]);
		return '<'.$m[1].$m[2].'>'.$content.$m[4];
	}

// -------------------------------------------------------------
	function fBr($m)
	{
		$content = preg_replace("@(.+)(?<!<br>|<br />)\n(?![#*;:\s|])@", '$1<br />', $m[3]);
		return '<'.$m[1].$m[2].'>'.$content.$m[4];
	}

// -------------------------------------------------------------
	function block($text)
	{
		$find = $this->btag;
		$tre = join('|', $find);

		$text = explode("\n\n", $text);

		$tag = 'p';
		$atts = $cite = $graf = $ext = '';
		$eat = false;

		$out = array();

		foreach($text as $line) {
			$anon = 0;
			if (preg_match("/^($tre)($this->a$this->c)\.(\.?)(?::(\S+))? (.*)$/s", $line, $m)) {
				// last block was extended, so close it
				if ($ext)
					$out[count($out)-1] .= $c1;
				// new block
				list(,$tag,$atts,$ext,$cite,$graf) = $m;
				list($o1, $o2, $content, $c2, $c1, $eat) = $this->fBlock(array(0,$tag,$atts,$ext,$cite,$graf));

				// leave off c1 if this block is extended, we'll close it at the start of the next block
				if ($ext)
					$line = $o1.$o2.$content.$c2;
				else
					$line = $o1.$o2.$content.$c2.$c1;
			}
			else {
				// anonymous block
				$anon = 1;
				if ($ext or !preg_match('/^ /', $line)) {
					list($o1, $o2, $content, $c2, $c1, $eat) = $this->fBlock(array(0,$tag,$atts,$ext,$cite,$line));
					// skip $o1/$c1 because this is part of a continuing extended block
					if ($tag == 'p' and !$this->hasRawText($content)) {
						$line = $content;
					}
					else {
						$line = $o2.$content.$c2;
					}
				}
				else {
					$line = $this->graf($line);
				}
			}

			$line = $this->doPBr($line);
			$line = preg_replace('/<br>/', '<br />', $line);

			if ($ext and $anon)
				$out[count($out)-1] .= "\n".$line;
			elseif(!$eat)
				$out[] = $line;

			if (!$ext) {
				$tag = 'p';
				$atts = '';
				$cite = '';
				$graf = '';
				$eat = false;
			}
		}
		if ($ext) $out[count($out)-1] .= $c1;
		return join("\n\n", $out);
	}

// -------------------------------------------------------------
	function fBlock($m)
	{
		extract($this->regex_snippets);
		list(, $tag, $att, $ext, $cite, $content) = $m;
		$atts = $this->pba($att);

		$o1 = $o2 = $c2 = $c1 = '';
		$eat = false;

		if( $tag === 'p' ) {
			# Is this an anonymous block with a note definition?
			$notedef = preg_replace_callback("/
					^note\#               #  start of note def marker
					([$wrd:-]+)           # !label
					([*!^]?)              # !link
					({$this->c})          # !att
					\.[\s]+               #  end of def marker
					(.*)$                 # !content
				/x$mod", array(&$this, "fParseNoteDefs"), $content);
			if( empty($notedef) ) # It will be empty if the regex matched and ate it.
				return array($o1, $o2, $notedef, $c2, $c1, true);
			}

		if (preg_match("/fn(\d+)/", $tag, $fns)) {
			$tag = 'p';
			$fnid = empty($this->fn[$fns[1]]) ? $fns[1] : $this->fn[$fns[1]];

			# If there is an author-specified ID goes on the wrapper & the auto-id gets pushed to the <sup>
			$supp_id = '';
			if (strpos($atts, ' id=') === false)
				$atts .= ' id="fn' . $fnid . '"';
			else
				$supp_id = ' id="fn' . $fnid . '"';

			if (strpos($atts, 'class=') === false)
				$atts .= ' class="footnote"';

			$backlink = (strpos($att, '^') === false) ? $fns[1] : '<a href="#fnrev' . $fnid . '">'.$fns[1].'</a>';
			$sup = "<sup$supp_id>$backlink</sup>";

			$content = $sup . ' ' . $content;
		}

		if ($tag == "bq") {
			$cite = $this->shelveURL($cite);
			$cite = ($cite != '') ? ' cite="' . $cite . '"' : '';
			$o1 = "\t<blockquote$cite$atts>\n";
			$o2 = "\t\t<p".$this->pba($att, '', 0).">";
			$c2 = "</p>";
			$c1 = "\n\t</blockquote>";
		}
		elseif ($tag == 'bc') {
			$o1 = "<pre$atts>";
			$o2 = "<code".$this->pba($att, '', 0).">";
			$c2 = "</code>";
			$c1 = "</pre>";
			$content = $this->shelve($this->r_encode_html(rtrim($content, "\n")."\n"));
		}
		elseif ($tag == 'notextile') {
			$content = $this->shelve($content);
			$o1 = $o2 = '';
			$c1 = $c2 = '';
		}
		elseif ($tag == 'pre') {
			$content = $this->shelve($this->r_encode_html(rtrim($content, "\n")."\n"));
			$o1 = "<pre$atts>";
			$o2 = $c2 = '';
			$c1 = "</pre>";
		}
		elseif ($tag == '###') {
			$eat = true;
		}
		else {
			$o2 = "\t<$tag$atts>";
			$c2 = "</$tag>";
		}

		$content = (!$eat) ? $this->graf($content) : '';

		return array($o1, $o2, $content, $c2, $c1, $eat);
	}

// -------------------------------------------------------------
	function graf($text)
	{
		// handle normal paragraph text
		if (!$this->lite) {
			$text = $this->noTextile($text);
			$text = $this->code($text);
		}

		$text = $this->getRefs($text);
		$text = $this->links($text);
		if (!$this->noimage)
			$text = $this->image($text);

		if (!$this->lite) {
			$text = $this->table($text);
			$text = $this->lists($text);
		}

		$text = $this->span($text);
		$text = $this->footnoteRef($text);
		$text = $this->noteRef($text);
		$text = $this->glyphs($text);
		return rtrim($text, "\n");
	}

// -------------------------------------------------------------
	function span($text)
	{
		$qtags = array('\*\*','\*','\?\?','-','__','_','%','\+','~','\^');
		$pnct = ".,\"'?!;:";
		$this->span_depth++;

		if( $this->span_depth <= $this->max_span_depth )
		{
			foreach($qtags as $f)
			{
				$text = preg_replace_callback("/
					(^|(?<=[\s>$pnct\(])|[{[])            # pre
					($f)(?!$f)                            # tag
					({$this->c})                          # atts
					(?::(\S+))?                           # cite
					([^\s$f]+|\S.*?[^\s$f\n])             # content
					([$pnct]*)                            # end
					$f
					($|[\]}]|(?=[[:punct:]]{1,2}|\s|\)))  # tail
				/x", array(&$this, "fSpan"), $text);
			}
		}
		$this->span_depth--;
		return $text;
	}

// -------------------------------------------------------------
	function fSpan($m)
	{
		$qtags = array(
			'*'  => 'strong',
			'**' => 'b',
			'??' => 'cite',
			'_'  => 'em',
			'__' => 'i',
			'-'  => 'del',
			'%'  => 'span',
			'+'  => 'ins',
			'~'  => 'sub',
			'^'  => 'sup',
		);

		list(, $pre, $tag, $atts, $cite, $content, $end, $tail) = $m;

		$tag = $qtags[$tag];
		$atts = $this->pba($atts);
		$atts .= ($cite != '') ? 'cite="' . $cite . '"' : '';

		$content = $this->span($content);

		$opentag = '<'.$tag.$atts.'>';
		$closetag = '</'.$tag.'>';
		$tags = $this->storeTags($opentag, $closetag);
		$out = "{$tags['open']}{$content}{$end}{$tags['close']}";

		if (($pre and !$tail) or ($tail and !$pre))
			$out = $pre.$out.$tail;

		return $out;
	}

// -------------------------------------------------------------
	function storeTags($opentag,$closetag='')
	{
		$key = ($this->tag_index++);

		$key = str_pad( (string)$key, 10, '0', STR_PAD_LEFT ); # $key must be of fixed length to allow proper matching in retrieveTags
		$this->tagCache[$key] = array('open'=>$opentag, 'close'=>$closetag);
		$tags = array(
			'open'  => "textileopentag{$key} ",
			'close' => " textileclosetag{$key}",
		);
		return $tags;
	}

// -------------------------------------------------------------
	function retrieveTags($text)
	{
		$text = preg_replace_callback('/textileopentag([\d]{10}) /' , array(&$this, 'fRetrieveOpenTags'),  $text);
		$text = preg_replace_callback('/ textileclosetag([\d]{10})/', array(&$this, 'fRetrieveCloseTags'), $text);
		return $text;
	}

// -------------------------------------------------------------
	function fRetrieveOpenTags($m)
	{
		list(, $key ) = $m;
		return $this->tagCache[$key]['open'];
	}

// -------------------------------------------------------------
	function fRetrieveCloseTags($m)
	{
		list(, $key ) = $m;
		return $this->tagCache[$key]['close'];
	}

// -------------------------------------------------------------
	function placeNoteLists($text)
	{
		extract($this->regex_snippets);

		# Sequence all referenced definitions...
		if( !empty($this->notes) ) {
			$o = array();
			foreach( $this->notes as $label=>$info ) {
				$i = @$info['seq'];
				if( !empty($i) ) {
					$info['seq'] = $label;
					$o[$i] = $info;
				} else {
					$this->unreferencedNotes[] = $info;	# unreferenced definitions go here for possible future use.
				}
			}
			if( !empty($o) ) ksort($o);
			$this->notes = $o;
		}

		# Replace list markers...
		$text = preg_replace_callback("@<p>notelist({$this->c})(?:\:($wrd))?([\^!]?)(\+?)\.[\s]*</p>@U$mod", array(&$this, "fNoteLists"), $text );

		return $text;
	}

// -------------------------------------------------------------
	function fParseNoteDefs($m)
	{
		list(, $label, $link, $att, $content) = $m;

		# Assign an id if the note reference parse hasn't found the label yet.
		$id = @$this->notes[$label]['id'];
		if( !$id )
			$this->notes[$label]['id'] = uniqid(rand());

		if( empty($this->notes[$label]['def']) ) # Ignores subsequent defs using the same label
		{
			$this->notes[$label]['def'] = array(
				'atts'    => $this->pba($att),
				'content' => $this->graf($content),
				'link'    => $link,
			);
		}
		return '';
	}

// -------------------------------------------------------------
	function noteRef($text)
	{
		$text = preg_replace_callback("/
			\[                   #  start
			({$this->c})         # !atts
			\#
			([^\]!]+?)           # !label
			([!]?)               # !nolink
			\]
		/Ux", array(&$this, "fParseNoteRefs"), $text);
		return $text;
	}

// -------------------------------------------------------------
	function fParseNoteRefs($m)
	{
		#   By the time this function is called, all the defs will have been processed
		# into the notes array. So now we can resolve the link numbers in the order
		# we process the refs...

		list(, $atts, $label, $nolink) = $m;
		$atts = $this->pba($atts);
		$nolink = ($nolink === '!');

		# Assign a sequence number to this reference if there isn't one already...
		$num = @$this->notes[$label]['seq'];
		if( !$num )
			$num = $this->notes[$label]['seq'] = ($this->note_index++);

		# Make our anchor point & stash it for possible use in backlinks when the
		# note list is generated later...
		$this->notes[$label]['refids'][] = $refid = uniqid(rand());

		# If we are referencing a note that hasn't had the definition parsed yet, then assign it an ID...
		$id = @$this->notes[$label]['id'];
		if( !$id )
			$id = $this->notes[$label]['id'] = uniqid(rand());

		# Build the link (if any)...
		$_ = '<span id="noteref'.$refid.'">'.$num.'</span>';
		if( !$nolink )
			$_ = '<a href="#note'.$id.'">'.$_.'</a>';

		# Build the reference...
		$_ = '<sup'.$atts.'>'.$_.'</sup>';

		return $_;
	}

// -------------------------------------------------------------
	function fNoteLists($m)
	{
		list(, $att, $start_char, $g_links, $extras) = $m;
		if( !$start_char ) $start_char = 'a';
		$index = $g_links.$extras.$start_char;

		if( empty($this->notelist_cache[$index]) ) { # If not in cache, build the entry...
			$o = array();

			if( !empty($this->notes)) {
				foreach($this->notes as $seq=>$info) {
					$links = $this->makeBackrefLink($info, $g_links, $start_char );
					if( !empty($info['def'])) {
						$id = $info['id'];
						extract($info['def']);
						$o[] = "\t".'<li'.$atts.'>'.$links.'<span id="note'.$id.'"> </span>'.$content.'</li>';
					} else {
						$o[] = "\t".'<li'.$atts.'>'.$links.' Undefined Note [#'.$info['seq'].'].</li>';
					}
				}
			}
			if( '+' == $extras && !empty($this->unreferencedNotes) ) {
				foreach($this->unreferencedNotes as $seq=>$info) {
					if( !empty($info['def'])) {
						extract($info['def']);
						$o[] = "\t".'<li'.$atts.'>'.$content.'</li>';
					}
				}
			}

			$this->notelist_cache[$index] = join("\n",$o);
		}

		$_ = ($this->notelist_cache[$index]) ? $this->notelist_cache[$index] : '';

		if( !empty($_) ) {
			$list_atts = $this->pba($att);
			$_ = "<ol$list_atts>\n$_\n</ol>";
		}

		return $_;
	}

// -------------------------------------------------------------
	function makeBackrefLink( &$info, $g_links, $i )
	{
		$atts = $content = $id = $link = '';
		@extract( $info['def'] );
		$backlink_type = ($link) ? $link : $g_links;

		$i_ = strtr( $this->encode_high($i) , array('&'=>'', ';'=>'', '#'=>''));
		$decode = (strlen($i) !== strlen($i_));

		if( $backlink_type === '!' )
			return '';
		elseif( $backlink_type === '^' )
			return '<a href="#noteref'.$info['refids'][0].'"><sup>'.$i.'</sup></a>';
		else {
			$_ = array();
			foreach( $info['refids'] as $id ) {
 				$_[] = '<a href="#noteref'.$id.'"><sup>'. ( ($decode) ? $this->decode_high('&#'.$i_.';') : $i_ ) .'</sup></a>';
				$i_++;
			}
			$_ = join( ' ', $_ );
			return $_;
		}

		return '';
	}

// -------------------------------------------------------------
	function links($text)
	{
		return preg_replace_callback('/
			(^|(?<=[\s>.\(])|[{[]) # $pre
			"                      # start
			(' . $this->c . ')     # $atts
			([^"]+?)               # $text
			(?:\(([^)]+?)\)(?="))? # $title
			":
			('.$this->urlch.'+?)   # $url
			(\/)?                  # $slash
			([^\w\/;]*?)           # $post
			([\]}]|(?=\s|$|\)))
		/x', array(&$this, "fLink"), $text);
	}

// -------------------------------------------------------------
	function fLink($m)
	{
		list(, $pre, $atts, $text, $title, $url, $slash, $post, $tail) = $m;

		if( '$' === $text ) $text = $url;

		$atts = $this->pba($atts);
		$atts .= ($title != '') ? ' title="' . $this->encode_html($title) . '"' : '';

		if (!$this->noimage)
			$text = $this->image($text);

		$text = $this->span($text);
		$text = $this->glyphs($text);
		$url = $this->shelveURL($url.$slash);

		$opentag = '<a href="' . $url . '"' . $atts . $this->rel . '>';
		$closetag = '</a>';
		$tags = $this->storeTags($opentag, $closetag);
		$out = $tags['open'].trim($text).$tags['close'];

		if (($pre and !$tail) or ($tail and !$pre))
		{
			$out = $pre.$out.$post.$tail;
			$post = '';
		}

		return $this->shelve($out).$post;
	}

// -------------------------------------------------------------
	function getRefs($text)
	{
		return preg_replace_callback("/^\[(.+)\]((?:http:\/\/|\/)\S+)(?=\s|$)/Um",
			array(&$this, "refs"), $text);
	}

// -------------------------------------------------------------
	function refs($m)
	{
		list(, $flag, $url) = $m;
		$this->urlrefs[$flag] = $url;
		return '';
	}

// -------------------------------------------------------------
	function shelveURL($text)
	{
		if (!$text) return '';
		$ref = md5($text);
		$this->urlshelf[$ref] = $text;
		return 'urlref:'.$ref;
	}

// -------------------------------------------------------------
	function retrieveURLs($text)
	{
		return preg_replace_callback('/urlref:(\w{32})/',
			array(&$this, "retrieveURL"), $text);
	}

// -------------------------------------------------------------
	function retrieveURL($m)
	{
		$ref = $m[1];
		if (!isset($this->urlshelf[$ref]))
			return $ref;
		$url = $this->urlshelf[$ref];
		if (isset($this->urlrefs[$url]))
			$url = $this->urlrefs[$url];
		return $this->r_encode_html($this->relURL($url));
	}

// -------------------------------------------------------------
	function relURL($url)
	{
		$parts = @parse_url(urldecode($url));
		if ((empty($parts['scheme']) or @$parts['scheme'] == 'http') and
			 empty($parts['host']) and
			 preg_match('/^\w/', @$parts['path']))
			$url = $this->hu.$url;
		if ($this->restricted and !empty($parts['scheme']) and
				!in_array($parts['scheme'], $this->url_schemes))
			return '#';
		return $url;
	}

// -------------------------------------------------------------
	function isRelURL($url)
	{
		$parts = @parse_url($url);
		return (empty($parts['scheme']) and empty($parts['host']));
	}

// -------------------------------------------------------------
	function image($text)
	{
		return preg_replace_callback("/
			(?:[[{])?		   # pre
			\!				   # opening !
			(\<|\=|\>)? 	   # optional alignment atts
			($this->c)		   # optional style,class atts
			(?:\. )?		   # optional dot-space
			([^\s(!]+)		   # presume this is the src
			\s? 			   # optional space
			(?:\(([^\)]+)\))?  # optional title
			\!				   # closing
			(?::(\S+))? 	   # optional href
			(?:[\]}]|(?=\s|$|\))) # lookahead: space or end of string
		/x", array(&$this, "fImage"), $text);
	}

// -------------------------------------------------------------
	function fImage($m)
	{
		list(, $algn, $atts, $url) = $m;
		$url = htmlspecialchars($url);
		$atts  = $this->pba($atts);
		$atts .= ($algn != '')	? ' align="' . $this->iAlign($algn) . '"' : '';
		$atts .= (isset($m[4])) ? ' title="' . $m[4] . '"' : '';
		$atts .= (isset($m[4])) ? ' alt="'	 . $m[4] . '"' : ' alt=""';
		$size = false;
		if ($this->isRelUrl($url))
			$size = @getimagesize(realpath($this->doc_root.ltrim($url, $this->ds)));
		if ($size) $atts .= " $size[3]";

		$href = (isset($m[5])) ? $this->shelveURL($m[5]) : '';
		$url = $this->shelveURL($url);

		$out = array(
			($href) ? '<a href="' . $href . '">' : '',
			'<img src="' . $url . '"' . $atts . ' />',
			($href) ? '</a>' : ''
		);

		return $this->shelve(join('',$out));
	}

// -------------------------------------------------------------
	function code($text)
	{
		$text = $this->doSpecial($text, '<code>', '</code>', 'fCode');
		$text = $this->doSpecial($text, '@', '@', 'fCode');
		$text = $this->doSpecial($text, '<pre>', '</pre>', 'fPre');
		return $text;
	}

// -------------------------------------------------------------
	function fCode($m)
	{
		@list(, $before, $text, $after) = $m;
		return $before.$this->shelve('<code>'.$this->r_encode_html($text).'</code>').$after;
	}

// -------------------------------------------------------------
	function fPre($m)
	{
		@list(, $before, $text, $after) = $m;
		return $before.'<pre>'.$this->shelve($this->r_encode_html($text)).'</pre>'.$after;
	}

// -------------------------------------------------------------
	function shelve($val)
	{
		$i = uniqid(rand());
		$this->shelf[$i] = $val;
		return $i;
	}

// -------------------------------------------------------------
	function retrieve($text)
	{
		if (is_array($this->shelf))
			do {
				$old = $text;
				$text = strtr($text, $this->shelf);
			 } while ($text != $old);

		return $text;
	}

// -------------------------------------------------------------
// NOTE: deprecated
	function incomingEntities($text)
	{
		return preg_replace("/&(?![#a-z0-9]+;)/i", "x%x%", $text);
	}

// -------------------------------------------------------------
// NOTE: deprecated
	function encodeEntities($text)
	{
		return (function_exists('mb_encode_numericentity'))
		?	 $this->encode_high($text)
		:	 htmlentities($text, ENT_NOQUOTES, "utf-8");
	}

// -------------------------------------------------------------
// NOTE: deprecated
	function fixEntities($text)
	{
		/*	de-entify any remaining angle brackets or ampersands */
		return str_replace(array("&gt;", "&lt;", "&amp;"),
			array(">", "<", "&"), $text);
	}

// -------------------------------------------------------------
	function cleanWhiteSpace($text)
	{
		$out = preg_replace("/^\xEF\xBB\xBF|\x1A/", '', $text); # Byte order mark (if present)
		$out = preg_replace("/\r\n?/", "\n", $out); # DOS and MAC line endings to *NIX style endings
		$out = preg_replace("/^[ \t]*\n/m", "\n", $out);	# lines containing only whitespace
		$out = preg_replace("/\n{3,}/", "\n\n", $out);	# 3 or more line ends
		$out = preg_replace("/^\n*/", "", $out);		# leading blank lines
		return $out;
	}

// -------------------------------------------------------------
	function doSpecial($text, $start, $end, $method='fSpecial')
	{
		return preg_replace_callback('/(^|\s|[[({>])'.preg_quote($start, '/').'(.*?)'.preg_quote($end, '/').'(\s|$|[\])}])?/ms',
			array(&$this, $method), $text);
	}

// -------------------------------------------------------------
	function fSpecial($m)
	{
		// A special block like notextile or code
		@list(, $before, $text, $after) = $m;
		return $before.$this->shelve($this->encode_html($text)).$after;
	}

// -------------------------------------------------------------
	function noTextile($text)
	{
		 $text = $this->doSpecial($text, '<notextile>', '</notextile>', 'fTextile');
		 return $this->doSpecial($text, '==', '==', 'fTextile');

	}

// -------------------------------------------------------------
	function fTextile($m)
	{
		@list(, $before, $notextile, $after) = $m;
		#$notextile = str_replace(array_keys($modifiers), array_values($modifiers), $notextile);
		return $before.$this->shelve($notextile).$after;
	}

// -------------------------------------------------------------
	function footnoteRef($text)
	{
		return preg_replace('/(?<=\S)\[([0-9]+)([\!]?)\](\s)?/Ue',
			'$this->footnoteID(\'\1\',\'\2\',\'\3\')', $text);
	}

// -------------------------------------------------------------
	function footnoteID($id, $nolink, $t)
	{
		$backref = '';
		if (empty($this->fn[$id])) {
			$this->fn[$id] = $a = uniqid(rand());
			$backref = 'id="fnrev'.$a.'" ';
		}

		$fnid = $this->fn[$id];

		$footref = ( '!' == $nolink ) ? $id : '<a href="#fn'.$fnid.'">'.$id.'</a>';
		$footref = '<sup '.$backref.'class="footnote">'.$footref.'</sup>';

		return $footref;
	}

// -------------------------------------------------------------
	function glyphs($text)
	{
		// fix: hackish -- adds a space if final char of text is a double quote.
		$text = preg_replace('/"\z/', "\" ", $text);

		$text = preg_split("@(<[\w/!?].*>)@Us", $text, -1, PREG_SPLIT_DELIM_CAPTURE);
		$i = 0;
		foreach($text as $line) {
			// text tag text tag text ...
			if (++$i % 2) {
				// raw < > & chars are already entity encoded in restricted mode
				if (!$this->restricted) {
					$line = $this->encode_raw_amp($line);
					$line = $this->encode_lt_gt($line);
				}
				$line = preg_replace($this->glyph_search, $this->glyph_replace, $line);
			}
			$glyph_out[] = $line;
		}
		return join('', $glyph_out);
	}

// -------------------------------------------------------------
	function replaceGlyphs($text)
	{
		return preg_replace('/glyph:([^<]+)/','$1',$text);
	}

// -------------------------------------------------------------
	function iAlign($in)
	{
		$vals = array(
			'<' => 'left',
			'=' => 'center',
			'>' => 'right');
		return (isset($vals[$in])) ? $vals[$in] : '';
	}

// -------------------------------------------------------------
	function hAlign($in)
	{
		$vals = array(
			'<'  => 'left',
			'='  => 'center',
			'>'  => 'right',
			'<>' => 'justify');
		return (isset($vals[$in])) ? $vals[$in] : '';
	}

// -------------------------------------------------------------
	function vAlign($in)
	{
		$vals = array(
			'^' => 'top',
			'-' => 'middle',
			'~' => 'bottom');
		return (isset($vals[$in])) ? $vals[$in] : '';
	}

// -------------------------------------------------------------
// NOTE: used in notelists
	function encode_high($text, $charset = "UTF-8")
	{
		return mb_encode_numericentity($text, $this->cmap(), $charset);
	}

// -------------------------------------------------------------
// NOTE: used in notelists
	function decode_high($text, $charset = "UTF-8")
	{
		return mb_decode_numericentity($text, $this->cmap(), $charset);
	}

// -------------------------------------------------------------
// NOTE: deprecated
	function cmap()
	{
		$f = 0xffff;
		$cmap = array(
			0x0080, 0xffff, 0, $f);
		return $cmap;
	}

// -------------------------------------------------------------
	function encode_raw_amp($text)
	 {
		return preg_replace('/&(?!#?[a-z0-9]+;)/i', '&amp;', $text);
	}

// -------------------------------------------------------------
	function encode_lt_gt($text)
	 {
		return strtr($text, array('<' => '&lt;', '>' => '&gt;'));
	}

// -------------------------------------------------------------
	function encode_html($str, $quotes=1)
	{
		$a = array(
			'&' => '&amp;',
			'<' => '&lt;',
			'>' => '&gt;',
		);
		if ($quotes) $a = $a + array(
			"'" => '&#39;', // numeric, as in htmlspecialchars
			'"' => '&quot;',
		);

		return strtr($str, $a);
	}

// -------------------------------------------------------------
	function r_encode_html($str, $quotes=1)
	{
		// in restricted mode, input has already been escaped
		if ($this->restricted)
			return $str;
		return $this->encode_html($str, $quotes);
	}

// -------------------------------------------------------------
	function textile_popup_help($name, $helpvar, $windowW, $windowH)
	{
		return ' <a target="_blank" href="http://www.textpattern.com/help/?item=' . $helpvar . '" onclick="window.open(this.href, \'popupwindow\', \'width=' . $windowW . ',height=' . $windowH . ',scrollbars,resizable\'); return false;">' . $name . '</a><br />';

		return $out;
	}

// -------------------------------------------------------------
// NOTE: deprecated
	function txtgps($thing)
	{
		if (isset($_POST[$thing])) {
			if (get_magic_quotes_gpc()) {
				return stripslashes($_POST[$thing]);
			}
			else {
				return $_POST[$thing];
			}
		}
		else {
			return '';
		}
	}

// -------------------------------------------------------------
// NOTE: deprecated
	function dump()
	{
		static $bool = array( 0=>'false', 1=>'true' );
		foreach (func_get_args() as $a)
			echo "\n<pre>",(is_array($a)) ? print_r($a) : ((is_bool($a)) ? $bool[(int)$a] : $a), "</pre>\n";
		return $this;
	}

// -------------------------------------------------------------

	function blockLite($text)
	{
		$this->btag = array('bq', 'p');
		return $this->block($text."\n\n");
	}


} // end class
