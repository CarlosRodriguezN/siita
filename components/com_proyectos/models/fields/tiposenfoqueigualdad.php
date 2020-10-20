<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldTiposEnfoqueIgualdad extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'EnfoqueIgualdad';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( '   td.intId_enfoque as id, 
                            upper( td.strNombre_enfoque ) as nombre ' );
        
        $query->from( '#__gen_enfoque td' );
        $query->where( 'td.intId_enfoquePadre = 1' );
        $query->where( 'td.intId_enfoque != 2' );
        $query->where( 'td.intId_enfoque != 5' );
        $query->order( 'td.strNombre_enfoque' );

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