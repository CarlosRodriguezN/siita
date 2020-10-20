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
class JFormFieldEnfoqueEcorae extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'enfoqueecorae';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( '   ee.intId_dim as id,
                            upper( ee.strDescripcion_dim ) as nombre' );
        $query->from(   '#__gen_dimension AS ee' );
        $query->where(  'ee.intId_enfoque = 5' );
        $query->where(  'ee.published = 1' );
        $query->order(  'ee.strDescripcion_dim' );
     
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