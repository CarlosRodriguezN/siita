<?php

class tocken
{
    public function __construct()
    {
        ;
    }
    
    /**
     * 
     * Creo Token de validacion a partir de los siguientes datos:
     *      idUrl
     *      idInstitucion
     *      idFuncionario
     *      Lista de IP's asociadas a la institucion que tiene acceso al sistema
     * 
     * @param Array $dtaUrl
     * 
     * @return string
     * 
     */
    public function crearToken( $dta )
    {
        return md5( $dta );
    }

}