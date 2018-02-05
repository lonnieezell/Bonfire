<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2018, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Class docSearch
 *
 * Implements basic search capabilities for Bonfire docs. Includes application,
 * core bonfire, and module docs.
 */
class DocSearch
{
    /** @var object The CodeIgniter instance. */
    protected $ci;

    /** @var integer Minimum characters that can be submitted for a search. */
    protected $min_chars = 3;

    /** @var integer Maximum characters that can be submitted for a search. */
    protected $max_chars = 30;

    /** @var string Valid file extensions we can search in. */
    protected $allowed_file_types = 'html|htm|php|php4|php5|txt|md';

    /** @var array Which files should we skip over during our search? */
    protected $skip_files = array('.', '..', '_404.md', '_toc.ini');

    /**
     * How much of each file should we read. Use lower values for faster searches.
     * @var integer
     */
    protected $byte_size = 51200;

    /**
     * Number of characters long (approximately) the result excerpt should be.
     * @var integer
     */
    protected $excerpt_length = 250;

    /** @var integer The maximum number of results allowed from a single file. */
    protected $max_per_file = 1;

    //--------------------------------------------------------------------------

    /**
     * Constructor loads the text helper and CommonMark library.
     */
    public function __construct()
    {
        $this->ci =& get_instance();

        $this->ci->load->helper('text');
        $this->ci->load->library('CommonMark');
    }

    /**
     * The entry point for performing a search of the documentation.
     *
     * @param string $terms
     * @param array $folders
     *
     * @return array|null
     */
    public function search($terms = null, $folders = array())
    {
        if (empty($terms) || empty($folders)) {
            return null;
        }

        $this->ci->load->helper('directory');

        $results = array();
        foreach ($folders as $folder) {
            $results = array_merge($results, $this->searchFolder($terms, $folder));
        }

        return $results;
    }

    /**
     * Searches a single directory worth of files.
     *
     * @param string $term
     * @param string $folder
     *
     * @return array The results.
     */
    private function searchFolder($term, $folder)
    {
        $results = array();
        $map = bcDirectoryMap($folder, 2);

        // Make sure we have something to work with.
        if (empty($map) || ! is_array($map)) {
            return array();
        }

        // Loop over each file and search the contents for our term.
        foreach ($map as $dir => $file) {
            $file_count = 0;

            if (in_array($file, $this->skip_files)) {
                continue;
            }

            // Is it a folder?
            if (is_array($file) && count($file)) {
                $results = array_merge($results, $this->searchFolder($term, "{$folder}/{$dir}"));
                continue;
            }

            // Make sure it's the right file type...
            if (! preg_match("/({$this->allowed_file_types})/i", $file)) {
                continue;
            }

            $path      = $folder .'/'. $file;
            $term_html = htmlentities($term);

            // Read in the file text
            $handle = fopen($path, 'r');
            $text   = fread($handle, $this->byte_size);

            // Do we have a match in here somewhere?
            $found = stristr($text, $term) || stristr($text, $term_html);

            if (! $found) {
                continue;
            }

            // Escape our terms to safely use in a preg_match.
            $excerpt   = strip_tags($text);
            $term      = preg_quote($term);
            $term      = str_replace("/", "\/", "{$term}");
            $term_html = preg_quote($term_html);
            $term_html = str_replace("/", "\/", "{$term_html}");

            // Add the item to our results with extracts.
            if (preg_match_all(
                "/((\s\S*){0,3})($term|$term_html)((\s?\S*){0,3})/i",
                $excerpt,
                $matches,
                PREG_OFFSET_CAPTURE | PREG_SET_ORDER
            )) {
                foreach ($matches as $match) {
                    if ($file_count >= $this->max_per_file) {
                        continue;
                    }

                    // Remove trailing directory separator.
                    $apppath = rtrim(APPPATH, DIRECTORY_SEPARATOR);
                    $filename = str_replace('.md', '', $file);

                    // Does $folder contain BFPATH?
                    if (strpos($folder, BFPATH) !== false) {
                        // Does $folder contain BFPATH . 'docs'?
                        if (strpos($folder, BFPATH . 'docs') !== false) {
                            $result_url = str_replace(BFPATH . 'docs', '', $folder);
                            $result_url = '/docs/developer' . $result_url . '/' .  $filename;
                        } elseif (strpos($folder, BFPATH . 'modules/') !== false) {
                            // Does $folder contain BFPATH . 'modules/'?
                            // Does $folder end with '/docs/developer'?
                            if (substr($folder, -strlen('/docs/developer')) == '/docs/developer') {
                                // Remove '/docs/developer' from $folder.
                                $result_url  = str_replace('/docs/developer', '', $folder);
                                $result_url  = str_replace(BFPATH . 'modules/', '/docs/developer/', $result_url);
                                $result_url .= '/' . $filename;
                            } else {
                                $result_url  = str_replace(BFPATH . 'modules/', '/docs/application/', $folder);
                                // Remove trailing 'docs' from $result_url.
                                $result_url  = rtrim($result_url, 'docs');
                                $result_url .= $filename;
                            }
                        }
                    } elseif (strpos($folder, APPPATH . 'docs') !== false) {
                        // Does $folder contain APPATH?
                        $result_url = str_replace(APPPATH . 'docs', '', $folder);
                        $result_url = '/docs/application' . $result_url . '/' .  $filename;
                    } elseif (strpos($folder, $apppath . '/modules/') !== false) {
                        // $folder contains $apppath /modules/.
                        // Does $folder end with '/docs/developer'?
                        if (substr($folder, -strlen('/docs/developer')) == '/docs/developer') {
                            // Remove '/docs/developer' from $folder.
                            $result_url  = str_replace('/docs/developer', '', $folder);
                            $result_url  = str_replace($apppath . '/modules/', '/docs/developer/', $result_url);
                            $result_url .= '/' . $filename;
                        } else {
                            $result_url  = str_replace($apppath . '/modules/', '/docs/application/', $folder);
                            // Remove trailing 'docs' from $result_url.
                            $result_url  = rtrim($result_url, 'docs');
                            $result_url .= $filename;
                        }
                    }

                    $results[] = array(
                        'title'   => $this->extractTitle($excerpt, $file),
                        'file'    => $folder .'/'. $file,
                        'url'     => $result_url,
                        'extract' => $this->buildExtract($excerpt, $term, $match[0][0]),
                    );

                    ++$file_count;
                }
            }
        }

        return $results;
    }

    /**
     * Handles extracting the text surrounding our match and basic match formatting.
     *
     * @param string $excerpt
     * @param string $term
     * @param string $match_string
     *
     * @return string
     */
    private function buildExtract($excerpt, $term, $match_string)
    {
        // Find the character positions within the string that the match was found.
        // That way we'll know from what positions before and after this we want to grab it in.
        $start_offset = stripos($excerpt, $match_string);

        // Modify the start and end positions based on $this->excerpt_length / 2.
        $buffer = floor($this->excerpt_length / 2);

        // Adjust our start position.
        $start_offset = $start_offset - $buffer;
        if ($start_offset < 0) {
            $start_offset = 0;
        }

        $extract = substr($excerpt, $start_offset);
        $extract = strip_tags($this->ci->commonmark->convert($extract));
        $extract = character_limiter($extract, $this->excerpt_length);

        // Wrap the search term in a span we can style.
        $extract = str_ireplace($term, '<span class="term-hilight">' . $term . '</span>', $extract);

        return $extract;
    }

    /**
     * Extracts the title from a bit of markdown formatted text. If it doesn't
     * have an h1 or h2, then it uses the filename.
     *
     * @param string $excerpt
     * @param string $file
     *
     * @return string
     */
    private function extractTitle($excerpt, $file)
    {
        $title = '';

        // Easiest to work if this is split into lines.
        $lines = explode("\n", $excerpt);

        if (! empty($lines) && is_array($lines)) {
            foreach ($lines as $line) {
                if (strpos($line, '# ') === 0 || strpos($line, '## ') === 0) {
                    $title = trim(str_replace('#', '', $line));
                    break;
                }
            }
        }

        // If it's empty, we'll use the filename.
        if (empty($title)) {
            $title = str_replace('_', ' ', $file);
            $title = str_replace('.md', ' ', $title);
            $title = ucwords($title);
        }

        return $title;
    }
}
