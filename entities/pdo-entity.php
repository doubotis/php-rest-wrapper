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

class PDOEntity {
    
    protected $_obj;
    
    public static function parseStatement($sth) {
        $sth->execute();
        $res = $sth->fetchAll();
        
        $entity = new PDOEntity();
        $entity->setObject($res);
        return $entity;
    }
    
    public function setObject($obj) {
        self::$_obj = $obj;
    }
    
    public function getObject() {
        return self::$_obj;
    }
}

?>