<?php

// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );
// import the Joomla modellist library
jimport( 'joomla.application.component.modellist' );

jimport( 'ecorae.objetivos.objetivo.acciones.acciones' );
jimport( 'ecorae.objetivos.objetivo.objetivos' );

JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_panel' . DS . 'tables' );

/**
 * PEI List Model
 */
class PanelModelUnidadGestion extends JModelList
{
    public function __construct( $config = array( ) )
    {
        parent::__construct( $config );
    }
    
    /**
     *  Lista las Poas que tiene una unidad de gestion
     * 
     * @param int       $idEntidadUg    Id de la entidad de unidad de gestión
     * @return type
     * 
     */
    public function lstPoasUG( $idEntidadUg )
    {
        $tbPlan = $this->getTable( 'Plan', 'PanelTable' );
        $lstPoasUg = $tbPlan->getLstPoas($idEntidadUg);
        
        if ($lstPoasUg) {
            foreach ($lstPoasUg AS $key=>$poaUg) {
                $poaUg->idRegPoa = $key;
                $objetivos = new Objetivos();
                //  Identificador del tipo de entidad POA
                $idTpoEntidad = 2;
                if( $objetivos ) {
                    $lstObjsPoa = $objetivos->getLstObjetivos( $poaUg->idPoa, $idTpoEntidad );
                    
                    $poaUg->lstObjetivos = $lstObjsPoa;
                }
            }
        }
        return $lstPoasUg;
    }
    
    
    /**
     * 
     * Retorna una lista de Unidades de Gestion identificadas por su entidad 
     * Activas
     * 
     * @return type
     */
    public function lstUG()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select( '   t1.intIdentidad_ent AS idEntidad, 
                            t1.strNombre_ug AS nombre' );
        $query->from( '#__gen_unidad_gestion t1');
        $query->where( 't1.published = 1' );
        $query->order( 't1.strNombre_ug' );
        
        $db->setQuery( (string)$query );
        $db->query();
            
        $lstProgramas = ( $db->getNumRows() > 0 )   ? $db->loadObjectList() 
                                                    : FALSE;
        
        return $lstProgramas;
    }
    
    
    
    
    public function lstObjetivosOEIUG( $intCodigo_ug )
    {
        return;
    }
    
    
}