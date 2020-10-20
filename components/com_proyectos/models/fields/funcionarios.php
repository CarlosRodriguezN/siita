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
class JFormFieldFuncionarios extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'funcionarios';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getInput()
    {
        //  Obtengo el identificador del funcionario
        $idFuncionario = $this->form->getField( 'idResponsable' )->value;

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select( 'fnc.intcodigo_fnc as id, 
                         UPPER( CONCAT( fnc.strapellido_fnc, " ", fnc.strnombre_fnc ) ) as nombre' );
        $query->from( '#__gen_funcionario fnc' );
        $query->where( 'fnc.published = 1' );

        $db->setQuery( (string)$query );
        $db->query();
        
        $messages = $db->loadObjectList();
        
        //  Creo el combo de Funcionarios y pongo en la opcion "selected" el sector 
        //  perteneciente a un determinado subsector
        $options  = '<select id="'. $this->id .'" name="'. $this->name .'">';
        $options  .= '<option value ="">'. JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_FUNCIONARIO_TITLE' ) .'</option>';
        
        foreach( $messages as $value ){
            $selected = ( $idFuncionario == $value->id )? 'selected'
                                                        : '';

            $options .= '<option value="'. $value->id .'" '. $selected .'>'. $value->nombre .'</option>';
        }
        
        $options .= "</select>";
        
        return $options;
    }
}