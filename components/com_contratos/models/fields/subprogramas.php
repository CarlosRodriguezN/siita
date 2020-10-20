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
class JFormFieldSubProgramas extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'subProgramas';
  
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $intcodigo_prg=JRequest::getvar('intcodigo_prg');
    
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( 'tsp.intId_SubPrograma AS id, 
                            UPPER( tsp.strAlias ) AS nombre' );
        $query->from( '#__pfr_sub_programa AS tsp' );
        $query->where( "tsp.intcodigo_prg = {$intcodigo_prg}" );
        $query->where( 'tsp.published = 1' );
    
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