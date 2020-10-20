<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldEstadocp extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'estadocp';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( 'ecp.inpCodigo_estado as id, 
                         upper( ecp.strDescripcion_estado ) as nombre' );
        $query->from( '#__gen_estados as ecp' );
        $query->where( 'ecp.published = 1' );
    
        $db->setQuery((string)$query);
        $messages = $db->loadObjectList();
        $options = array();
        if ($messages)
        {
            foreach($messages as $message) 
            {
                $options[] = JHtml::_( 'select.option', 
                                        strip_tags($message->id), 
                                        strip_tags($message->nombre) );
            }
        }
        
        $options = array_merge( parent::getOptions(), $options );
        
        return $options;
    }
}