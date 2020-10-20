<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldPlanesNacionales extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'planesnacionales';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( 'pn.INTCODIGO_PN as id, 
                         upper( pn.STRDESCRIPCION_PN ) as nombre' );
        $query->from( '#__pdn_plannacional pn' );
        $query->where( 'pn.published = 1 and pn.BLNVIGENTE_PN = 1' );
    
        $db->setQuery((string)$query);
        $messages = $db->loadObjectList();
        $options = array();
        if ($messages)
        {
            foreach($messages as $message) 
            {
                $options[] = JHtml::_( 'select.option', 
                                        $message->id, 
                                        strtoupper( $message->nombre ) );
            }
        }
        
        $options = array_merge( parent::getOptions(), $options );
        
        return $options;
    }
}