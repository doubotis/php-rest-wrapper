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
namespace Doubotis\PHPRestWrapper\Responses;

require_once 'classes/responses/APIResponse.php';

class APIStructuredResponse extends APIResponse {
    
    protected $_data;
    protected $_delay;
    
    function __construct() {
        
    }
    
    public function setData($data) {
        $this->_data = $data;
    }
    
    public function setDelay($delay) {
        $this->_delay = $delay;
    }
    
    public function asXML() {
        $xml = new SimpleXMLElement('<root/>');
        array_to_xml($arr,$xml);
        return $xml->asXML();
    }
    
    public function asJSON() {
        return json_encode($arr);
    }

}

?>