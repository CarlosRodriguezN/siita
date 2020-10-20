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
class JFormFieldInstituciones extends JFormFieldList
{
    /**
    * The field type.
    *
    * @var		string
    */
    protected $type = 'instituciones';
 
    /**
    * Method to get a list of options for a list input.
    *
    * @return	array		An array of JHtml options.
    */
    protected function getOptions() 
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        
        $query->select( '   t2.intCodigo_ins AS idInstitucion,
                            t2.intCodigo_ug AS idUndGestion,
                            t2.tb_intCodigo_ug AS idPadre,
                            t1.strNombre_ins AS institucion, 
                            t1.strAlias_ins AS aliasInstitucion,
                            t2.strNombre_ug AS undGestion,
                            t2.strAlias_ug AS aliasUndGestion' );
        $query->from( '#__gen_institucion inst' );
        $query->join( 'INNER', '#__gen_unidad_gestion t2 ON t1.intCodigo_ins = t2.intCodigo_ins' );
        $query->where( 't2.intCodigo_ug > 0 ' ); 
        $query->where( 't1.intCodigo_ins > 0' ); 
        $query->where( 't1.published = 1' ); 
        $query->where( 't2.published = 1' ); 

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