<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class User extends NativeImplementation implements IGet
{
    public function init() {
        
    }
    
    public function dealloc() {
        
    }
    
    public function get($request) {
        return array("testGet2" => "testGet2");
    }
}


?>