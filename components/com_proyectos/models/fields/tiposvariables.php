<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * 
 * Retorna una lista Tipos de Variables
 * 
 */
class JFormFieldTiposVariables extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'tiposvariables';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select( '   tv.inpcodigo_tipovar as id,
                            upper( tv.strdescripcion_tipovar ) as nombre' );
        $query->from( '#__gen_tipo_variable tv' );
        $query->where( 'tv.published = 1' );
        $query->order( 'tv.strdescripcion_tipovar' );

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