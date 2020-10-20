<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * 
 *  HelloWorld Form Field class for the HelloWorld component
 * 
 */
class JFormFieldUnidadesAnalisis extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'unidadesanalisis';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getInput() 
    {
        $idUndAnalisis = $this->form->getField( 'inpCodigo_unianl' )->value;
        
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( 'ua.inpcodigo_unianl as id, 
                         upper( ua.strdescripcion_unianl ) as nombre' );
        $query->from( '#__gen_unidad_analisis ua' );
        $query->where( 'ua.published = 1' );
        $query->order( 'ua.strdescripcion_unianl' );
    
        $db->setQuery((string)$query);
        $messages = $db->loadObjectList();
        
        //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
        //  perteneciente a un determinado subsector
        $options  = '<select id="'. $this->id .'" name="'. $this->name .'">';
        $options .= '<option value="0">'. JText::_( 'COM_MANTENIMIENTO_FIELD_ATRIBUTO_UNDANALISIS_TITLE' ) .'</option>';

        foreach( $messages as $value ){
            $selected = ( $idUndAnalisis == $value->id )? 'selected'
                                                        : '';

            $options .= '<option value="'. $value->id .'" '. $selected .'>'. $value->nombre .'</option>';
        }
        
        $options .= "</select>";
        
        return $options;
    }
}