<?php
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldFiscalizadores extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'fiscalizadores';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( 'fzc.intIdFiscalizador_fc AS id, 
                         upper( CONCAT( per.strApellidos_pc," ", per.strNombres_pc) ) AS  nombre' );
        $query->from( '#__ctr_fiscalizador AS fzc' );
        $query->leftJoin( '#__ctr_persona AS per ON per.intIdPersona_pc= fzc.intIdPersona_pc' );
        $query->where( 'fzc.published = 1' );
    
        $db->setQuery((string)$query);
        $messages = $db->loadObjectList();
        $options = array();
        if ($messages)
        {
            foreach($messages as $message) 
            {
                $options[] = JHtml::_( 'select.option', 
                                        strip_tags($message->id), 
                                        strip_tags($message->nombre));
            }
        }
        
        $options = array_merge( parent::getOptions(), $options );
        
        return $options;
    }
}