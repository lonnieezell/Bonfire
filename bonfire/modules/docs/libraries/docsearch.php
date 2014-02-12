<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class docSearch
 *
 * Implements basic search capabilities for Bonfire docs. Includes application,
 * core bonfire, and module docs.
 */
class docSearch {

    protected $ci;

    /**
     * Minimum characters that can be submitted for a search.
     * @var int
     */
    protected $min_chars    = 3;

    /**
     * Maximum characters that can be submitted for a search.
     * @var int
     */
    protected $max_chars    = 30;

    /**
     * Valid file extensions we can search in.
     * @var string
     */
    protected $allowed_file_types = 'html|htm|php|php4|php5|txt|md';

    /**
     * Which files should we skip over during our search?
     * @var array
     */
    protected $skip_files   = array('.', '..', '_404.md', '_toc.ini');

    /**
     * How much of each file should we read.
     * Use lower values for faster searches.
     * @var int
     */
    protected $byte_size        = 51200;

    /**
     * Number of characters long (approximately)
     * the result excerpt should be.
     * @var int
     */
    protected $excerpt_length   = 250;

    /**
     * The maximum number of results allowed from a single file.
     * @var int
     */
    protected $max_per_file     = 1;

    //--------------------------------------------------------------------

    public function __construct ()
    {
        $this->ci =& get_instance();

        $this->ci->load->helper('text');
        $this->ci->load->helper('markdown_extended');
    }
    
    //--------------------------------------------------------------------

    /**
     * The entry point for performing a search of the documentation.
     *
     * @param null $terms
     * @param array $folders
     *
     * @return array|null
     */
    public function search ($terms=null, $folders=array())
    {
        if (empty($terms) || empty($folders))
        {
            return NULL;
        }

        $this->ci->load->helper('directory');

        $results = array();

        foreach ($folders as $folder)
        {
            $results = array_merge($results, $this->search_folder($terms, $folder) );
        }

        return $results;
    }

    //--------------------------------------------------------------------

    /**
     * Searches a single directory worth of files.
     *
     * @param $term
     * @param $folder
     *
     * @return array The results.
     */
    private function search_folder ($term, $folder)
    {
        $results = array();

        $map = directory_map($folder, 2);

        // Make sure we have something to work with.
        if ( ! is_array($map) || (is_array($map) && ! count($map)))
        {
            return array();
        }

        // Loop over each file and search the contents for our term.
        foreach ($map as $dir => $file)
        {
            $file_count = 0;

            if (in_array($file, $this->skip_files))
            {
                continue;
            }

            // Is it a folder?
            if (is_array($dir) && count($dir))
            {
                $results = array_merge($results, $this->search_folder($terms, $folder .'/'. $dir));
                continue;
            }

            // Make sure it's the right file type...
            if ( ! preg_match("/({$this->allowed_file_types})/i", $file))
            {
                continue;
            }

            $path       = $folder .'/'. $file;
            $term_html  = htmlentities($term);

            // Read in the file text
            $handle     = fopen($path, 'r');
            $text       = fread($handle, $this->byte_size);

            // Do we have a match in here somewhere?
            $found      = stristr($text, $term) || stristr($text, $term_html);

            if ( ! $found)
            {
                continue;
            }

            // Escape our terms to safely use in a preg_match
            $excerpt    = strip_tags($text);
            $term       = preg_quote($term);
            $term       = str_replace("/", "\/", "{$term}");
            $term_html  = preg_quote($term_html);
            $term_html  = str_replace("/", "\/", "{$term_html}");

            // Add the item to our results with extracts.
            if ( preg_match_all("/((\s\S*){0,3})($term|$term_html)((\s?\S*){0,3})/i", $excerpt, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER) )
            {
                foreach ($matches as $match)
                {
                    if ($file_count >= $this->max_per_file)
                    {
                        continue;
                    }

                    $result_url = '/docs/'. str_replace('docs', '', $folder) . str_replace('.md', '', $file);
                    $result_url = str_replace(BFPATH, 'developer/', $result_url);
                    $result_url = str_replace(APPPATH, 'application/', $result_url);

                    $results[] = array(
                        'title'     => $this->extract_title($excerpt, $file),
                        'file'      => $folder .'/'. $file,
                        'url'       =>  $result_url,
                        'extract'   => $this->build_extract($excerpt, $term, $match[0][0])
                    );

                    $file_count++;
                }
            }
        }

        return $results;
    }

    //--------------------------------------------------------------------

    /**
     * Handles extracting the text surrounding our match and basic match formatting.
     *
     * @param $excerpt
     * @param $term
     * @param $match_string
     *
     * @return string
     */
    private function build_extract ($excerpt, $term, $match_string)
    {
        // Find the character positions within the string that our match was found at.
        // That way we'll know from what positions before and after this we want to grab it in.
        $start_offset   = stripos($excerpt, $match_string);

        // Modify the start and end positions based on $this->excerpt_length / 2.
        $buffer = floor($this->excerpt_length / 2);

        // Adjust our start position
        $start_offset = $start_offset - $buffer;
        if ($start_offset < 0) $start_offset = 0;

        $extract = substr($excerpt, $start_offset);

        $extract = strip_tags( MarkdownExtended($extract) );

        $extract = character_limiter($extract, $this->excerpt_length);

        // Wrap the search term in a span we can style.
        $extract = str_ireplace($term, '<span class="term-hilight">'. $term .'</span>', $extract);

        return $extract;
    }

    //--------------------------------------------------------------------

    /**
     * Extracts the title from a bit of markdown formatted text. If it doesn't
     * have an h1 or h2, then it uses the filename.
     *
     * @param $excerpt
     * @param $file
     * @return string
     */
    private function extract_title ($excerpt, $file)
    {
        $title = '';

        // Easiest to work if this is split into lines.
        $lines = explode("\n", $excerpt);

        if (is_array($lines) && count($lines))
        {
            foreach ($lines as $line)
            {
                if (strpos($line, '# ') === 0 || strpos($line, '## ') === 0)
                {
                    $title = trim( str_replace('#', '', $line) );
                    break;
                }
            }
        }

        // If it's empty, we'll use the filename.
        if (empty($title))
        {
            $title = str_replace('_', ' ', $file);
            $title = str_replace('.md', ' ', $title);
            $title = ucwords($title);
        }

        return $title;
    }

    //--------------------------------------------------------------------

}