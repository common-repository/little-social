<?php

class Logger {

    protected $errorFile;

    public function __construct($message) {
        $this->errorFile = plugin_dir_path( __FILE__ ) . 'social_errors.txt';

        if(!$this->checkLogFileExists()) {
            $this->createLogFile();
            
        }

        if(!$this->writeToLog($message)) {
            return false;
        }
        
    }

    protected function checkLogFileExists() {
        return (file_exists($this->errorFile) == 1);
    }

    protected function createLogFile() {
        return (file_put_contents($this->errorFile, "") == 1);
    }

    protected function writeToLog($string) {
        $string = date('d-m-Y H:i:s') . " " . $string;
        return (file_put_contents($this->errorFile, $string.PHP_EOL."\n\n" , FILE_APPEND) == 1);
    }
}
