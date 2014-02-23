<?php namespace Support;

class Path
{

    /**
     * Function to remove a trailing slash / or \
     * from a given path
     * 
     * @param  string $path path to operate on
     * @return string
     */
    public static function removeTrailingSlash($path)
    {
        if (substr($path, -1, 1) == "/") {

            $path = substr($path, 0, strlen($path)-1);

        } else if (substr($path, -1, 1) == "\\") {

            $path = substr($path, 0, strlen($path)-1);
        }

        return $path;
    }

    
    /**
     * Function to take a path and ensure it is 
     * absolute 
     *
     * @param  string $path     path to make absolute
     * @param  string $basePath path relative paths should be inside
     * @return string
     */
    public static function absolute($path, $basePath)
    {
        
        $absolutePattern = "/^(?:\/|\\\\|\w:\\\\|\w:\/).*$/";

        //path is already absolute
        if (preg_match($absolutePattern, $path)) {

            $result = $path;

        //path is relative, join to make absolute
        } else {

            $basePath = static::removeTrailingSlash($basePath);

            $result = implode(DIRECTORY_SEPARATOR, [$basePath, $path]);
        }

        return $result;
    }
}
