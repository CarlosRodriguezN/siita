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
class JFormFieldProgramas extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var  string
    */
    protected $type = 'programas';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( 'prg.intCodigo_prg as id, 
                         upper( prg.strNombre_prg ) as nombre' );
        $query->from( '#__pfr_programa prg' );
        $query->where( 'prg.published = 1' );

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