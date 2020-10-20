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
    protected function getOptions() 
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( '   fun.intId_fnc as id,
                            CONCAT_WS( " ", fun.strapellido_fnc, fun.strnombre_fnc ) as nombre' );
        $query->from( '#__gen_funcionario fun' );
        $query->where( 'fun.inpvigente = 1' );
    
        echo $query->__toString(); exit;
        
        $db->setQuery((string)$query);
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