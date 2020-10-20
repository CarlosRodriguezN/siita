<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 *  Modelo Programas
 */
class ProgramaModelProgramas extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            //  Creamos un arreglo por con los campos por los cuales vamos a realizar los filtros, 
            //  en este caso seria nombre, descripcion, estado( publicado / despublicado )
            $config['filter_fields'] = array('strDescripcion_prg',
                'published');
        }

        parent::__construct($config);
    }

    public function getItems() {
        //  Joomla ejecuta la funcion getItems de la clase JModelList
        //  la cual por defecto busca la funcion "getListQuery" y 
        //  retorna la informacion dentro de un objeto
        $items = parent::getItems();

        //  Recorro dicho objeto y a la propiedad "url" le asigno la url 
        //  de edicion de cada elemento de este conjunto de resultados
        //  Lo hacemos de esta manera para no hacerlo en la vista, 
        //  con esto mantenemos a nuestra vista ordenada
        foreach ($items as &$item) {
            $item->url = JRoute::_('index.php?option=com_programa&amp;task=programa.edit&amp;intCodigo_prg=' . $item->codigoPrograma );
        }

        return $items;
    }

    /**
     *
     * Retorno informacion de la tabla "TB_PFR_PROGRAMA".
     * 
     * @return Object 
     */
    public function getListQuery() {
        //  Llama a la funcion getListQuery del padre en este caso del objeto JModelList, 
        //  el cual retorna un objeto que nos permite hacer una consulta a la BBDD        
        $query = parent::getListQuery();

        $query->select('   t1.intCodigo_prg         AS codigoPrograma,
                           t3.strdescripcion_te     AS tipoEntidad ,
                           t1.strDescripcion_prg    AS descripcionPrograma,
                           t1.strNombre_prg         AS nombre,
                           t1.published,
                            u.name');
        $query->from('#__pfr_programa t1');

        //  Verificamos si algun registro esta siendo editado por algun usuario
        $query->join( 'INNER', '#__gen_entidad t2 ON t1.intIdEntidad_ent = t2.intIdEntidad_ent');

        //  Verificamos si algun registro esta siendo editado por algun usuario
        $query->join( 'INNER', '#__gen_tipo_entidad t3 ON t2.intIdtipoentidad_te = t3.intIdtipoentidad_te');
        
        $query->join( 'INNER', '#__prg_ug_responsable t4 ON t1.intCodigo_prg = t4.intCodigo_prg');

        //  Verificamos si algun registro esta siendo editado por algun usuario
        $query->leftJoin('#__users as u on u.id = t1.checked_out');
        
        //  Aumento el filtro para que no se muestre el programa sin definir.
        $query->where( 't1.intCodigo_prg > 0' );
        
        if( JFactory::getUser()->id != 147 ){
            $query->where( 't4.intCodigo_ug IN (    SELECT DISTINCT tb.intCodigo_ug
                                                    FROM #__gen_funcionario ta
                                                    JOIN #__gen_ug_funcionario tb ON ta.intCodigo_fnc = tb.intCodigo_fnc
                                                    WHERE ta.intIdUser_fnc = '. JFactory::getUser()->id .' )' );
        }
        
        //
        //  Filtro estado del contenido ( publicado / despublicado )
        //
        //  Setea el valor de la variable de session "filter.search" 
        //  obtenida desde el metodo populateState
        //
        $published = $this->getState('filter.published');

        if ($published == '') {
            $query->where('( t1.published = 1 OR t1.published = 0 )');
        } else if ($published != '*') {
            $published = (int) $published;
            $query->where("t1.published = '" . $published . "'");
        }

        //
        //  Filtro por palabra o frase
        //
        //  Setea el valor de la variable de session "filter.search" 
        //  obtenida desde el metodo populateState
        $search = $this->getState('filter.search');
        $db = $this->getDbo();
     
        
        if (!empty($search)) {
            //  Prepara la cadena existente en la variable $search para que pueda ser ejecutada 
            //  en una sentencia Sql, con esto se previene de un ataque XSS
            $search = '%' . $db->getEscaped($search, true) . '%';

            $field_searches = " t1.strDescripcion_prg LIKE '" . $search . "' OR "
                            . " t3.strdescripcion_te LIKE '" . $search . "'";

            //  Adjuntamos via metodo where las opciones de busqueda registradas en al variable "$field_searches"
            $query->where($field_searches, 'OR' );
        }

        //
        //  Filtro para ordenar por columna seleccionada
        //
        $orderCol = $this->getState('list.ordering');
        $orderDirn = $this->getState('list.direction');

        if ($orderCol != '') {
            $query->order($db->getEscaped($orderCol . ' ' . $orderDirn));
        }

        return $query;
    }

    /**
     *
     * Se encarga de gestionar ( capturar / actualizar ) las variables para los diferentes filtros 
     * 
     * @param type $ordering    define el orden para la presentacion de la informacion ( Ascendente / Descendente )
     * @param type $direction
     * 
     */
    protected function populateState($ordering = null, $direction = null) {
        //
        //  Crea la variable de session "context.filter.search", y setea el valor ingresado por el usuario 
        //  en la caja de texto llamada "filter_search", en caso de existir esta variable simplemente actualiza 
        //  el contenido
        //
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published');
        $this->setState('filter.published', $published);

        parent::populateState($ordering, $direction);
    }

}