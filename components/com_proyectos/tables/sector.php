<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */

class ProyectosTableSector extends JTable
{
    /**
    * Constructor
    *
    *   @param object Database connector object
    * 
    */
    function __construct( &$db ) 
    {
        parent::__construct( '#__gen_sector', 'inpCodigo_sec', $db );
    }
    
    /**
     *  Retorna una lista de Sectores pertenecientes a un determinado MacroSector
     *  @return type 
     */
    public function getSectores( $idMS, $idSIV )
    {
        $db = $this->getDbo();
        
        $sql = "SELECT  sec.inpCodigo_sec as id, 
                        sec.strDescripcion_sec as nombre
                FROM ". $db->nameQuote( "#__gen_sector" ) ." sec
                WHERE intId_macrosector = '". $idMS ."' AND sec.intId_si = " . $idSIV;

        $db->setQuery( $sql );
        $db->query();
        
        if( $db->getNumRows() > 0 ){
            $result = $db->loadObjectList();

            $dtaInicio["id"]    = 0;
            $dtaInicio["nombre"]= JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_SECTOR_TITLE' );

            $result[] = (object)$dtaInicio; 
        }else{
            $dtaInicio["id"]    = 0;
            $dtaInicio["nombre"]= JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_SINREGISTROS_TITLE' );

            $result[] = (object)$dtaInicio;
        }

        return $result;
    }
    
    /**
     *  Retorna una lista de SubSectores pertenecientes a un determinado Sector
     * 
     *  @return type 
     */
    public function getSubSectores( $idSector, $idSIV )
    {
        $db = $this->getDbo();
        
        $sql = "SELECT  sec.inpcodigo_subsec as id, 
                        sec.strdescripcion_subsec as nombre
                FROM ". $db->nameQuote( "#__gen_subsector" ) ." sec
                WHERE inpcodigo_sec = '" . $idSector . "' AND sec.intId_si = " . $idSIV;

        $db->setQuery( $sql );
        $db->query();
        
        if( $db->getNumRows() > 0 ){
            $result = $db->loadObjectList();

            $dtaInicio["id"]    = 0;
            $dtaInicio["nombre"]= JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_SUBSECTOR_TITLE' );

            $result[] = (object)$dtaInicio; 
        }else{
            $dtaInicio["id"]    = 0;
            $dtaInicio["nombre"]= JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_SINREGISTROS_TITLE' );

            $result[] = (object)$dtaInicio;
        }

        return $result;
    }

}