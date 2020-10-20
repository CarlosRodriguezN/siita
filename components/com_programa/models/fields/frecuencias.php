<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldFrecuencias extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'frecuencias';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( '   t1.intcodigo_per as id, 
                            t1.strdescripcion_per as nombre' );
        
        $query->from( '#__gen_periodicidad t1' );
        $query->where( 't1.published = 1' );
        $query->order( 't1.strdescripcion_per' );
        
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