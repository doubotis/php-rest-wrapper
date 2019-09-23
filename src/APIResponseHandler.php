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
namespace Doubotis\PHPRestWrapper;

include_once __DIR__ . "/APIRequestImplementation.php";
include_once __DIR__ . "/HTTPException.php";

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class APIResponseHandler
{
    private $_obj = null;
    private $_dispatcher = null;
    
    function __construct($dispatcher) {
        $this->_dispatcher = $dispatcher;
    }
    
    public function handleRequest($request) {
        
        try {
            
            $h = $this->_dispatcher->getClassForRequest($request);

            if ($request->getHTTPMethod() == "GET" && method_exists($h, "get")) {
                $obj = $h->get($request);
            } else if ($request->getHTTPMethod() == "POST" && method_exists($h, "post")) {
                $obj = $h->post($request);
            } else if ($request->getHTTPMethod() == "PUT" && method_exists($h, "put")) {
                $obj = $h->put($request);
            } else if ($request->getHTTPMethod() == "DELETE" && method_exists($h, "delete")) {
                $obj = $h->delete($request);
            }

            $this->_obj = $obj;
            
        } catch (Exception $ex) {
            $this->_obj = array("exception" => $e->getMessage());
        }
    }
    
    public function getResponse() {
        return $this->_obj;
    }
}


?>
