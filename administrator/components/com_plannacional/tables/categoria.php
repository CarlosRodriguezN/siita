<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * Clase Tabla Categoria
 */
class PlanNacionalTableCategoria extends JTable
{
    /**
    * Constructor
    *
    * @param object Database connector object
    */
    
    
    function __construct(&$db) 
    {
        parent::__construct('#__gen_categoria', 'INTCODIGOCATEGORIA', $db);
    }
    
    
    public function getLstCategorias()
    {
        //  Llama a la funcion getListQuery del padre en este caso del objeto JModelList, 
        //  el cual retorna un objeto que nos permite hacer una consulta a la BBDD        
        //  $query = parent::getListQuery();
        $db = JFactory::getDBO();
        $query = $db->getQuery( true );
        
        
        $query->select( 'c.*, u.name' );
        $query->from( '#__gen_categoria c' );
        
        //  Verificamos si algun registro esta siendo editado por algun usuario
        $query->leftJoin( '#__users as u on u.id = c.checked_out' );
        $db->setQuery( $query->toString() );
        
        $data = $query->loadObjectList();
        
        var_dump( $data ); exit; 
        
        //  $query->query();
        
        return $query->loadObjectList();

    }
    
    
}