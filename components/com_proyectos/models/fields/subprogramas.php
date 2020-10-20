<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldSubProgramas extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'subprogramas';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getInput()
    {
        $idPrograma = $this->form->getField( 'intCodigo_prg' )->value;
        
        $options   = '<select id="'. $this->id .'" name="'. $this->name .'">';

        if( $idPrograma > 0 ){
            $idSubPrograma = $this->form->getField( 'intCodigo_sprg' )->value;
            
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select( '   t1.intId_SubPrograma AS id, 
                                UPPER( t1.strDescripcion_sprg ) AS nombre' );
            $query->from( '#__pfr_sub_programa t1' );
            $query->where( 't1.intCodigo_prg = '. $idPrograma );
            $query->where( 't1.published = 1' );
            $query->order( 't1.strDescripcion_sprg' );

            $db->setQuery( (string)$query );
            $db->query();
            
            if( $db->getNumRows() > 0 ){
                $messages = $db->loadObjectList();

                //  Creo el combo de Sectores y pongo en la opcion "selected" el sector 
                //  perteneciente a un determinado subsector
                $options  .= '  <option value ="0">'. JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_SUBPROGRAMAS_TITLE' ) .'</option>';

                foreach( $messages as $value ){
                    $selected = ( $idSubPrograma == $value->id )? 'selected'
                                                                : '';

                    $options .= '<option value="'. $value->id .'" '. $selected .'>'. $value->nombre .'</option>';
                }
            }else{
                $options  .= '  <option value ="0">'. JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_SINREGISTROS_TITLE' ) .'</option>';
            }
        
        }else{
            $options  .= '  <option value ="0">'. JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_SUBPROGRAMAS_TITLE' ) .'</option>';
        }
        
        $options .= "</select>";
        
        return $options;
    }
}