<?php

/**
 * MvcFramework
 *
 * @link        https://github.com/joncarlmatthews/MvcFramework for the canonical source repository
 * @copyright   
 * @link        Coded to the Zend Framework Coding Standard for PHP 
 *              http://framework.zend.com/manual/1.12/en/coding-standard.html
 * 
 * File format: UNIX
 * File encoding: UTF8
 * File indentation: Spaces (4). No tabs
 *
 */

namespace MvcFramework\Utility\FileSystem
{

    /**
     * The Directory class provides methods for dealing with directories.
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Utility\FileSystem
     */
    class Directory
    {
        static public function makeDirectory($directoryPath, $withPermissions = 0775)
        {
            if (is_dir($directoryPath)){
                return true;
            }
            
            // Convert \ to /.
            $directoryPath = preg_replace('/\\\/', '/', $directoryPath);
            
            // Explode the directory path.
            $directoryPaths = explode('/', $directoryPath);
            
            // Loop through and create.
            $concat = null;
            foreach($directoryPaths as $directoryPath){
                if ( (!empty($directoryPath)) && (strlen($directoryPath) > 0) ){
                
                    // Unix?
                    if ('/' == DIRECTORY_SEPARATOR){
                        $concat .= '/' . $directoryPath . '/';
                    }else{
                        // ... Windows.
                        $concat .= $directoryPath . '/';
                    }
                    if (!is_dir($concat)){
                        $mkdir = @mkdir($concat, $withPermissions);
                        if (!$mkdir){
                            throw new \Exception('
                                    Cannot create directory "' . $directoryPath . '". 
                                    Check permissions of parent directory.
                                ');
                        }
                    }
                }
            }
            return true;
        }
    }    
}