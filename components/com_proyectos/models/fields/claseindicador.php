<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * 
 *  Clase de Indicador Form Field class for the HelloWorld component
 * 
 */
class JFormFieldClaseIndicador extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'claseindicador';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( 'ci.inpCodigo_claseInd as id, 
                         upper( ci.strDescripcion_claseInd ) as nombre' );
        $query->from( '#__ind_clase_indicador ci' );
        $query->where( 'ci.inpCodigo_claseInd > 0' );
        $query->where( 'ci.published = 1' );
    
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