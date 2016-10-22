<?php

class InfoClass {
    
    protected $_defaultSep = "\n";
    protected $_errors = false;
    protected $_log = [];

    public function clearError() {
        $this->_errors = false;
    }

    public function setError( $str ) {
        $this->_errors[] = $str;
        return $this;
    }

    public function addLog($str) {
        $this->_log[] = $str;
    }

    public function getError($sep = NULL) {
        if( $sep === NULL ) {
            $sep = $this->_defaultSep;
        }
        if( $this->_errors !== false ) {
            return implode($sep, $this->_errors);
        } else {
            return false;
        }
    }

    protected function getLog($sep = NULL) {
        if( $sep === NULL ) {
            $sep = $this->_defaultSep;
        }
        return implode($sep, $this->_log);
    }
}
