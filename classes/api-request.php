<?php

/* 
 * Copyright (C) 2015 Christophe
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class APIRequest {
    
    protected $_resource;
    protected $_extension;
    protected $_filter;
    protected $_sorting;
    protected $_completeURI;
    protected $_method;
    protected $_httpHeaders;
    
    function __construct($requestURI) {
                
        $requestURI = str_replace("/proj/api/v1", "", $requestURI);
        $dotPos = strpos($requestURI, ".", 0);
        if ($dotPos == false) {
            $extension = "json";
            $reqPos = strpos($requestURI, "?", 0);
            if ($reqPos == false) $reqPos = 9999999;
            $resource = substr($requestURI, 0, $reqPos-1);
            if ($resource == false) $resource = $requestURI;
        }
        else {
            $dotPos++;
            $reqPos = strpos($requestURI, "?", $dotPos);
            $extension = substr($requestURI, $dotPos, ($reqPos - $dotPos));
            if ($extension == false) $extension = substr($requestURI, $dotPos);
            $resource = substr($requestURI, 0, $dotPos-1);
        }
        

        $sorting = isset($_REQUEST["sort"]) ? $_REQUEST["sort"] : "";
        $filter = isset($_REQUEST["filter"]) ? $_REQUEST["filter"] : "";
        
        $this->_completeURI = $requestURI;
        $this->_resource = $resource;
        $this->_extension = $extension;
        $this->_filter = $filter;
        $this->_sorting = $sorting;
        $this->_filter = $filter;
        $this->_method = $_SERVER['REQUEST_METHOD'];
        $this->_httpHeaders = getallheaders();
        
    }
    
    public function getResource() {
        return $this->_resource;
    }
    
    public function getCompleteURI() {
        return $this->_completeURI;
    }
    
    public function getExtension() {
        return $this->_extension;
    }
    
    public function getFilter() {
        return $this->_filter;
    }
    
    public function getSorting() {
        return $this->_sorting;
    }
    
    public function getHTTPHeaders() {
        return $this->_httpHeaders;
    }
    
    public function getHTTPMethod() {
        return $this->_method;
    }
    
    public function toArray() {
        return array(
            "completeURI" => $this->_completeURI,
            "method" => $this->_method,
            "resource" => $this->_resource,
            "extension" => $this->_extension,
            "filter" => $this->_filter,
            "sorting" => $this->_sorting,
            "httpHeaders" => $this->_httpHeaders
        );
    }
    
}


?>
