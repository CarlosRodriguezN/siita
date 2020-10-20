<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * 
 * HelloWorld Form Field class for the HelloWorld component
 * 
 */
class JFormFieldTipoUnidadMedida extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'tipounidadmedida';
 
    /**
    * 
    *   Method to get a list of options for a list input.
    *
    *   @return	array   An array of JHtml options.
    * 
    */
    protected function getInput() 
    {
        $idUndMedida = $this->form->getField( 'intCodigo_unimed' )->value;
        $idTpoUM = $this->_getIdTipoUndMedida( $idUndMedida );
        
        $this->form->setFieldAttribute( 'intIdTpoUndMedida', 'default', $idTpoUM );
        
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( '   DISTINCT
                                t1.intId_tum                    AS id,
                                UPPER( t1.strDescripcion_tum )  AS nombre' );
        $query->from( '#__gen_tipo_unidad_medida t1' );
        $query->order( 't1.strDescripcion_tum' );
    
        $db->setQuery( (string)$query );
        $messages = $db->loadObjectList();
        
        //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
        //  perteneciente a un determinado subsector
        $options  = '<select id="'. $this->id .'" name="'. $this->name .'">';
        $options .= '<option value="0">'. JText::_( 'COM_MANTENIMIENTO_FIELD_ATRIBUTO_UNDMEDIDA_TITLE' ) .'</option>';

        foreach( $messages as $value ){
            $selected = ( $idTpoUM == $value->id )  ? 'selected'
                                                        : '';

            $options .= '<option value="'. $value->id .'" '. $selected .'>'. $value->nombre .'</option>';
        }
        
        $options .= "</select>";
        
        return $options;
    }
    
    
    
    private function _getIdTipoUndMedida( $idUndMedida )
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( '   t1.intId_tum AS id' );
        $query->from( '#__gen_tipo_unidad_medida t1' );
        $query->join( 'INNER', '#__gen_unidad_medida t2 ON t1.intId_tum = t2.intId_tum' );
        $query->where( 't2.intCodigo_unimed = '. $idUndMedida );

        $db->setQuery((string)$query);
        
        return $db->loadObject()->id;
    }
    
    
}