<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * Get Filenames by extension
 *
 * Reads the specified directory and builds an array containing the filenames.
 * Any sub-folders contained within the specified path are read as well.
 *
 * @access	public
 * @param	string	path to source
 * @param   array   extensions to be found
 * @param	bool	whether to include the path as part of the filename
 * @param	bool	internal variable to determine recursion status - do not use in calls
 * @return	array
 */
if ( ! function_exists('get_filenames_by_extension'))
{
	function get_filenames_by_extension($source_dir, $extensions = array(), $include_path = FALSE, $_recursion = FALSE)
	{
		static $_filedata = array();

		if ($fp = @opendir($source_dir))
		{
			// reset the array and make sure $source_dir has a trailing slash on the initial call
			if ($_recursion === FALSE)
			{
				$_filedata = array();
				$source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
			}

			while (FALSE !== ($file = readdir($fp)))
			{
				if (@is_dir($source_dir.$file) && strncmp($file, '.', 1) !== 0)
				{
					get_filenames_by_extension($source_dir.$file.DIRECTORY_SEPARATOR, $extensions, $include_path, TRUE);
				}
				elseif (strncmp($file, '.', 1) !== 0)
				{
					if(in_array(pathinfo($file, PATHINFO_EXTENSION), $extensions))
					{
					    $_filedata[] = ($include_path == TRUE) ? $source_dir.$file : $file;
					}
				}
			}
			return $_filedata;
		}
		else
		{
			return FALSE;
		}
	}
}


