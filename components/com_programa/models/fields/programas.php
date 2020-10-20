<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * 
 *  Lista de Tipos de Entidades
 * 
 */
class JFormFieldProgramas extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'Programas';
  
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( 'pfr.intcodigo_prg AS id, 
                            UPPER( pfr.strNombre_prg ) AS nombre' );
        $query->from( '#__pfr_programa AS pfr' );
        $query->where( 'pfr.published = 1' );
    
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