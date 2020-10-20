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
        $idUndGestion = $this->form->getField( 'intIdUGResponsable' )->value;
        $idResponsable= $this->form->getField( 'idResponsable' )->value;
        

        $db = JFactory::getDBO();
        $query = $db->getQuery( true );

        $query->select( '   intId_ugf AS id, 
                            UPPER( CONCAT( t2.strApellido_fnc, " ", t2.strNombre_fnc ) ) AS nombre' );
        $query->from( '#__gen_ug_funcionario t1' );
        $query->join( 'INNER', '#__gen_funcionario t2 ON t1.intCodigo_fnc = t2.intCodigo_fnc' );
        $query->where( 't1.intCodigo_ug = '. $idUndGestion );
        
        $db->setQuery( (string) $query );
        
        $db->query();

        $messages = $db->loadObjectList();

        //  Creo el combo de Funcionarios y pongo en la opcion "selected" el sector 
        //  perteneciente a un determinado subsector
        $options = '<select id="' . $this->id . '" name="' . $this->name . '">';
        $options .= '<option value ="">' . JText::_( 'COM_CONTRATOS_FIELD_ATRIBUTO_RESPONSABLE_TITLE' ) . '</option>';
        if( $messages ) {
            foreach( $messages as $value ) {
                $selected = ( $idResponsable == $value->id ) ? 'selected' : '';

                $options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->nombre . '</option>';
            }
        }

        $options .= "</select>";

        return $options;
    }
}