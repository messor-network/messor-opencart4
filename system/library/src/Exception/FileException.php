<?php

namespace src\Exception;

use src\Logger\Logger;
use src\Utils\File;
use src\Config\Path;

/**
 * File handling exception class
 */
class FileException extends \Exception {

    private $additionalData;

    /**
     * @param string $message
     * @param integer $code
     * @param \Exception|null $previous
     */
    public function __construct($message="", $code = 0, \Exception $previous = null, $additionalData = '') 
    {   
        $this->additionalData = $additionalData; 
        parent::__construct($message, $code, $previous);
    }

    /**
     * Exception while reading file
     *
     * @param string $string
     * @return void
     */
    public function readError($string='') 
    {
        $string = Logger::addTime(). "\t" . trim($string). "\n";
        $string = File::write(Path::ERROR ,$string);
    }

    /**
     * Error writing to file
     *
     * @param string $string
     * @return void
     */
    public function writeError() 
    {
        $string = Logger::addTime(). "\t" . trim($this->additionalData). "\n";
        $string = File::write(Path::ERROR ,$string);
    }

    /**
     * Set handler error
     *
     * @return void
     */
    public static function setErrorHandler()
    {
        set_error_handler(function($errno, $errstr, $errfile, $errline){
            $errString = "File: $errfile". "\t".
                         "line: $errline". "\t".
                         "text: $errstr";
            throw new FileException($errstr, $errno, null, $errString);
        });
    }

    public static function restoreErrorHandler() {
        restore_error_handler();
    }
}            