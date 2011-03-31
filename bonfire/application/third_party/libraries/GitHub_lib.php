<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author Philip Sturgeon
 * @created 31/03/2009
 * @updated 29/12/2009
 * @info http://develop.github.com/
 */

class GitHub_lib {
	
	// CodeIgniter instance
    private $CI;
    
    function __construct($url = '')
	{
        $this->CI =& get_instance();
        log_message('debug', 'GitHub class initialized');
    }
    
    /**
     * Grab all issues for a specific repository
     * 
     * @access	public
     * @param	string - a GitHub user
     * @param	string - a repository name
     * @param	string - the state of the issues to pull (open/closed)
     * @return	object - an object with all the repository's issues
     */
    public function project_issues($user = '', $repo = '', $state = 'open')
    {
    	$responce = $this->_fetch_data('http://github.com/api/v2/json/issues/list/'.$user.'/'.$repo.'/'.$state);
    	
    	if(empty($responce->issues))
    	{
    		return FALSE;
    	}
    	
    	return $responce->issues;
    }
    
    /**
     * Grab the info for a repository
     * 
     * @access	public
     * @param	string - a GitHub user
     * @param	string - a repository name
     * @return	object - an object with all the repository's info
     */
    public function repo_info($user = '', $repo = '')
    {
    	$responce = $this->_fetch_data('http://github.com/api/v2/json/repos/show/'.$user.'/'.$repo);
    	
    	if(empty($responce->repository))
    	{
    		return FALSE;
    	}
    	
    	return $responce->repository;
    }
    
    /**
     * Grab all refs for a specific repository
     * 
     * @access	public
     * @param	string - a GitHub user
     * @param	string - a repository name
     * @param	string - the repository reference to pull (tags/branches)
     * @return	object - an object with all the repository's references
     */
    public function repo_refs($user = '', $repo = '', $ref = 'tags')
    {
    	$responce = $this->_fetch_data('http://github.com/api/v2/json/repos/show/'.$user.'/'.$repo.'/'.$ref);
    	
    	if(empty($responce->$ref))
    	{
    		return FALSE;
    	}
    	
    	return $responce->$ref;
    }
	
	/**
     * Grab the info for a specific user
     * 
     * @access	public
     * @param	string - a GitHub user
     * @return	object - an object with all the user's info
     */
    public function user_info($user = '')
    {
    	$responce = $this->_fetch_data('http://github.com/api/v2/json/user/show/'.$user);
    	
    	if(empty($responce->user))
    	{
    		return FALSE;
    	}
    	
    	return $responce->user;
    }
	
	/**
     * Grab all commits by a user to a specific repository
     * 
     * @access	public
     * @param	string - a GitHub user
     * @param	string - a repository name
     * @param	string - the branch name (master by default)
     * @return	object - an object with all the branch's commits
     */
    public function user_timeline($user = '', $repo = '', $branch = 'master')
    {
    	$responce = $this->_fetch_data('http://github.com/api/v2/json/commits/list/'.$user.'/'.$repo.'/'.$branch);
    	
    	if(empty($responce->commits))
    	{
    		return FALSE;
    	}
    	
    	return $responce->commits;
    }
    
    /**
     * Search GitHub
     * 
     * @access	public
     * @param	string - the term to search for
     * @param	string - the language
     * @return	array  - an array with all the search results
     */
    public function search($term = '', $language = NULL)
    {
    	if(!empty($language) && is_string($language))
    	{
    		$language = strtolower($language);
    	}
    	
    	$responce = $this->_fetch_data('http://github.com/api/v1/json/search/'.$term);
    	
    	if(empty($responce->repositories) or !is_array($responce->repositories))
    	{
    		return FALSE;
    	}
    	
    	$results = array();
    	
    	foreach($responce->repositories as &$result)
    	{
    		if($language != strtolower($result->language)) continue;
    		$results[] = $result;
    	}
    	
    	return $results;
    }

	/**
     * Fetch the data from GitHub
     * 
     * @access	private
     * @param	string - the API URL to use
     * @return	object - a decoded JSON object with all the information (FALSE if nothing is returned)
     */
    private function _fetch_data($url)
    {
    	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$returned = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close ($ch);

		if ($status == '200'){
			return json_decode( $returned );
		} else {
			return false;
		}
	}
	
}

// END GitHub class