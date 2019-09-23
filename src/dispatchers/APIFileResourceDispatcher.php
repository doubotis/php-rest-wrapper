<?php

/* 
 * Copyright (C) 2019 doubo
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

namespace Doubotis\PHPRestWrapper\Dispatchers;

require_once 'APIBaseDispatcher.php';
require_once __DIR__ . '/../utils/strings.php';

class APIFileResourceDispatcher extends APIBaseDispatcher
{
    private $_patterns = array();
    
    function __construct($filePath) {
        //TODO Store vars into $GLOBALS.
        $this->_patterns = $this->obtainConfig($filePath);
    }
    
    public function getClassForRequest($request) {
        
        // Get the resource.
        $resource = $request->getResource();

        // Loop inside the patterns to find the asked resource handler.
        $c = -1;
        for ($i=0; $i < count($this->_patterns); $i++) {
            $p = $this->_patterns[$i]["pattern"];
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
        $class = $this->_patterns[$c]["class"];
        $file = __DIR__ . "/../impl/" . $class . ".php";
        if (file_exists($file))
        {
            include_once $file;

            // Testing the designed class exists within context.
            if (class_exists($class))
            {
                $h = new $class();
                return $h;
            }
            else {
                throw new Exception("Implementation class file error. Is the class '" . $class . "' well defined?");
            }
        }
        else {
            throw new Exception("Implementation file error. Is the file '" . $file . "' exists inside 'impl' directory?");
        }
        
    }
    
    function obtainConfig($filePath) {
        
        $patterns = array();
        $handle = fopen($filePath, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                
                if (\Doubotis\PHPRestWrapper\Utils\startsWithString($line, "#"))
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
            throw new Exception("Error opening file ." + $filePath);
        }
        
        return $patterns;
    }
    
    
}
