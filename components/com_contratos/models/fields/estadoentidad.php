<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldEstadoEntidad extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'estadoentidad';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getInput()  
    {
        $idContrato = $this->form->getField( 'intIdContrato_ctr' )->value;
        
        $idEstadoEntidad = ( $idContrato > 0 )  ? $this->_getIdEstadoEntidad( $idContrato )
                                                : 0;
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( 't1.intIdEstadoEnt_ee as id, 
                         upper( t1.strEstado_ee ) as nombre' );
        $query->from( '#__gen_tipo_estado_entidad t1' );
        $query->where( 't1.published = 1' );
        $query->order( 't1.strDescripcion_ee' );
        
        $db->setQuery((string)$query);
        $messages = $db->loadObjectList();
        
        //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
        //  perteneciente a un determinado subsector
        $options  = '<select id="'. $this->id .'" name="'. $this->name .'">';
        $options .= '<option value="0">'. JText::_( 'COM_CONTRATOS_FIELD_ESTADO_ENTIDAD_TITLE' ) .'</option>';

        foreach( $messages as $value ){
            $selected = ( $idEstadoEntidad == $value->id )  ? 'selected'
                                                            : '';

            $options .= '<option value="'. $value->id .'" '. $selected .'>'. $value->nombre .'</option>';
        }
        
        $options .= "</select>";
        
        return $options;
    }
    
    
    /**
     * 
     * Retorno el identificador de la entidad asociado a un proyecto
     * 
     * @param int $idContrato  Identificador de un proyecto
     * 
     * @return int
     * 
     */
    private function _getIdEstadoEntidad( $idContrato )
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( '   ta.intIdEstadoEnt_ee AS idEstadoEntidad' );
        $query->from( '#__gen_estado_entidad ta' );
        $query->where( 'ta.dtFchRegistro_ee = ( SELECT  MAX( t2.dtFchRegistro_ee ) as fchRegistro
                                                FROM tb_ctr_contrato t1 INNER JOIN tb_gen_estado_entidad t2 ON t1.intIdEntidad_ent = t2.intIdEntidad_ent 
                                                WHERE t1.intIdContrato_ctr = '. $idContrato .' ) ' );
        
        $db->setQuery( ( string )$query );  
        $dtaProyecto = $db->loadObject();
        
        return $dtaProyecto->idEstadoEntidad;
    }

}