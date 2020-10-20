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
class JFormFieldUnidadMedida extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'unidadmedida';
 
    /**
    * 
    *   Method to get a list of options for a list input.
    *
    *   @return	array   An array of JHtml options.
    * 
    */
    protected function getInput() 
    {
        $idTpoUM = $this->form->getField( 'intIdTpoUndMedida' )->value;
        $idUndMedida = $this->form->getField( 'intCodigo_unimed' )->value;
        
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( '   t1.intCodigo_unimed AS id,
                            CONCAT( UPPER( t1.strDescripcion_unimed ), " ( ", t1.strSimbolo_unimed , " ) " ) AS nombre' );
        $query->from( '#__gen_unidad_medida t1' );
        $query->where( 't1.intId_tum = '. $idTpoUM );
        $query->order( 't1.strDescripcion_unimed' );
    
        $db->setQuery( (string)$query );
        $messages = $db->loadObjectList();
        
        //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
        //  perteneciente a un determinado subsector
        $options  = '<select id="'. $this->id .'" name="'. $this->name .'">';
        $options .= '<option value="0">'. JText::_( 'COM_MANTENIMIENTO_FIELD_ATRIBUTO_UNDMEDIDA_TITLE' ) .'</option>';

        foreach( $messages as $value ){
            $selected = ( $idUndMedida == $value->id )  ? 'selected'
                                                        : '';

            $options .= '<option value="'. $value->id .'" '. $selected .'>'. $value->nombre .'</option>';
        }
        
        $options .= "</select>";
        
        return $options;
    }

}