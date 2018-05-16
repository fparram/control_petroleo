<?php

class Users_unegocio extends ActiveRecord{
    public function initialize()
    {
        //Relaciones
        //Un usuario tiene una unidad de negocio de cada una
        $this->belongs_to('unegocio');
    }
    
}