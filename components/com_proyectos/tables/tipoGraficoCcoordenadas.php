<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * 
 * Clase que gestiona informacion de la tabla Imagenes
 * 
 */
class ProyectosTableTipoGraficoCoordenadas extends JTable
{

    /**
     *   Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db)
    {
        parent::__construct('#__tg_coordenadas', 'intRel_Tg_Coordenada', $db);
    }

    
   public function saveUpdateGraficoCoordenada($dataForm){
       $data['intRel_Tg_Coordenada']=$dataForm[""];
       $data['intCodigo_pry']=$dataForm[""];
       $data['intId_tg']=$dataForm[""];
       $data['strDescripcionGrafico']=$dataForm[""];
       
       if($this->bind($data)){
           $this->save($data);
       }
       return $this->intRel_Tg_Coordenada;
   }


}