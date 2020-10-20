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
class JFormFieldVariableIndicador extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'variableindicador';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        //  Selecciono las variables en las que un funcionario es responsable 
        //  en un determinado plan
        $query->select( '   t2.intId_iv	AS id,
                            CONCAT( IF( t2.intIdTpoVariable_iv = 1, "(v)", "(i)" ), " ", t6.strNombre_var ) AS nombre' );
        $query->from( '#__ind_indicador t1' );
        $query->join( 'INNER', '#__ind_indicador_variables t2 ON t1.intCodigo_ind = t2.intCodigo_ind' );
        $query->join( 'INNER', '#__ind_variable_funcionario_responsable t3 ON t2.intId_iv = t3.intId_iv' );
        $query->join( 'INNER', '#__gen_ug_funcionario t4 ON t3.intId_ugf = t4.intId_ugf' );
        $query->join( 'INNER', '#__gen_funcionario t5 ON t4.intCodigo_fnc = t5.intCodigo_fnc' );
        $query->join( 'INNER', '#__gen_variables t6 ON t2.intIdVariable_var = t6.intIdVariable_var' );
        $query->where( 't1.intCodigo_ind = '. JRequest::getVar( 'idIndicador' ) );
        $query->where( 't5.intIdUser_fnc = '. JRequest::getVar( 'idUsr' ) );
        
        $db->setQuery((string)$query);
        $messages = $db->loadObjectList();
        $options = array();

        if ($messages){
            foreach($messages as $message) {
                $options[] = JHtml::_( 'select.option', 
                                        $message->id, 
                                        $message->nombre );
            }
        }
        
        $options = array_merge( parent::getOptions(), $options );
        
        return $options;
    }
}