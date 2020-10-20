<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldMetasNacionales extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'metasnacionales';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( 'c.ID_CODIGO_MN as id, 
                         upper( rb.STRDESCRIPCION_MN ) as nombre' );
        $query->from( '#__pdn_meta_nacional mn' );
        $query->where( 'mn.published = 1' );
    
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