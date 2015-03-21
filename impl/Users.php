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

class Users extends NativeImplementation implements IGet
{
    public function init() {
        // Override this.
        // Ideal area to initiate PDO objects or open files.
    }
    
    public function dealloc() {
        // Override this.
        // Ideal area to close opened PDO objects and opened files.
    }
    
    public function get($request) {
        return array("testGet" => "testGet");
    }
}


?>