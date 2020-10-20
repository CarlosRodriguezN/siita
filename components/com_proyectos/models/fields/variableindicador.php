<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * HelloWorld Form Field class for the HelloWorld component
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
        
        $query->select( '   t1.intIdVariable_var AS id, 
                            t2.strDescripcion_var AS nombre' );
        $query->from( '#__ind_variables_indicador t1' );
        $query->join( 'INNER', '#__gen_variables t2 ON t1.intIdVariable_var = t2.intIdVariable_var' );
        //  $query->where( 't1.intIdIndEntidad = ' );

        $db->setQuery( (string)$query );

        $query->query();
        
        $messages = $db->loadObjectList();
        $options = array();

        if ($messages)
        {
            foreach($messages as $message) 
            {
                $options[] = JHtml::_( 'select.option', 
                                        $message->id, 
                                        $message->nombre );
            }
        }
        
        $options = array_merge( parent::getOptions(), $options );
        return $options;
    }

}