<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldProvincias extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'provincias';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( '   DISTINCT dpa.intIDProvincia_dpa as id, 
                            dpa.strNombreProvincia_dpa as nombre' );
        
        $query->from( '#__vw_dpa dpa' );
        $query->where( 'dpa.intIDEntidad_dpa = 38' );
        $query->order( 'dpa.strNombreProvincia_dpa' );
        
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