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

include_once __DIR__ . "/api-impl.php";
include_once __DIR__ . "/api-exceptions.php";
include_once __DIR__ . "/../impl/_native.php";

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class APIResponseHandler
{
    private $_obj = null;
    
    function __construct($request) {
        $this->doImplementation($request);
    }
    
    private function doImplementation($request) {
        
        // Get the patterns config file array.
        $patterns = $this->obtainConfig();
        
        try {
            // Get the resource.
            $resource = $request->getResource();
            
            // Loop inside the patterns to find the asked resource handler.
            $c = -1;
            for ($i=0; $i < count($patterns); $i++) {
                $p = $patterns[$i]["pattern"];
                $p = str_replace("\r\n", "", $p);
                $p = "~^" . $p . "$~";
                $res = preg_match($p, $resource);
                if ($res == 1) {
                    $c = $i;
                    break;
                }
            }
            // No handler found.
            if ($c == -1)
                throw new Exception("Resource not found");
            
            // If a handler is found, let's try to include file.
            $class = $patterns[$c]["class"];
            $file = __DIR__ . "/../impl/" . $class . ".php";
            if (file_exists($file))
            {
                include_once $file;
                
                // Testing the designed class exists within context.
                if (class_exists($class))
                {
                    $h = new $class();
                    $m = $patterns[$c]["methods"];
                    if (!in_array($request->getHTTPMethod(), $m))
                            throw new Exception("Not managed!");
                    if ($request->getHTTPMethod() == "GET")
                        $obj = $h->get($request);
                    else if ($request->getHTTPMethod() == "POST")
                        $obj = $h->post($request);
                    else if ($request->getHTTPMethod() == "PUT")
                        $obj = $h->put($request);
                    else if ($request->getHTTPMethod() == "DELETE")
                        $obj = $h->delete($request);
                    
                    $this->_obj = $obj;
                }
                else {
                    throw new Exception("Implementation class file error. Is the class '" . $class . "' well defined?");
                }
            }
            else {
                throw new Exception("Implementation file error. Is the file '" . $file . "' exists inside 'impl' directory?");
            }
            
        } catch (Exception $e) {
            $this->_obj = array("exception" => $e->getMessage());
        }
    }
    
    public function obtainConfig() {
        
        $patterns = array();
        
        $handle = fopen(__DIR__ . "/../impl/_resource.txt", "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                
                if (str_starts_with($line, "#"))
                        continue;       // This is a comment line
                
                // process the line read.
                $step = 0;
                $seps = split(" ", $line);
                $methods = array();
                for ($i = 0; $i < count($seps); $i++) {
                    if ($seps[$i] == "GET" || $seps[$i] == "POST" || $seps[$i] == "DELETE" || $seps[$i] == "PUT")
                        array_push ($methods, $seps[$i]);
                    else {
                        $step = $i;
                        break;
                    }
                }
                $class = $seps[$step];
                $pattern = $seps[$step+1];
                array_push($patterns, array("methods" => $methods, "class" => $class, "pattern" => $pattern));
            }

            fclose($handle);
        } else {
            // error opening the file.
        }
        
        return $patterns;
    }
    
    public function getObject() {
        return $this->_obj;
    }
}


?>
