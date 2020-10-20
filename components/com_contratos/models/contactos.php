<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 * HelloWorldList Model
 */
class contratosModelContactos extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            //  Creamos un arreglo por con los campos por los cuales vamos a realizar los filtros, 
            //  en este caso seria nombre, descripcion, estado( publicado / despublicado )
            $config['filter_fields'] = array('intIdContacto_cc',
                'intIdCargo_cgo',
                'intIdContratista_cta',
                'intIdPersona_pc'
            );
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
            $item->url = JRoute::_('index.php?option=com_contratos&amp;task=contacto.edit&amp;intIdContacto_cc=' . $item->intIdContacto_cc);
        }
   
        return $items;
    }

    /**
     *
     * Retorno informacion de la tabla "fases".
     * 
     * @return Object 
     */
    public function getListQuery() {
        //  Llama a la funcion getListQuery del padre en este caso del objeto JModelList, 
        //  el cual retorna un objeto que nos permite hacer una consulta a la BBDD        
        $query = parent::getListQuery();

        $query->select('    cto.intIdContacto_cc            AS  idContacto,
                            cto.intIdCargo_cgo              AS  idCargo,
                            cgo.strDescripcion_cgo          AS  descCargo,
                            cto.intIdContratista_cta        AS  idContratista,
                            ctr.strContratista_cta          AS  contratista,
                            cto.intIdPersona_pc             AS  idPersona,
                            CONCAT(pers.strApellidos_pc," ",pers.strNombres_pc) AS persona,
                            cto.published');

       
        
        $query->from('#__ctr_contacto AS cto');
        
        // tipo de contratista
        $query->leftJoin('#__ctr_cargo AS cgo on cgo.intIdCargo_cgo = cto.intIdCargo_cgo');
        
        // tipo de contratista
        $query->leftJoin('#__ctr_contratista AS ctr on ctr.intIdContratista_cta = cto.intIdContratista_cta');
        
        // tipo de contratista
        $query->leftJoin('#__ctr_persona AS pers on pers.intIdPersona_pc = cto.intIdPersona_pc');

        //
        //  Filtro estado del contenido ( publicado / despublicado )
        //
        //  Setea el valor de la variable de session "filter.search" 
        //  obtenida desde el metodo populateState
        //
        $published = $this->getState('filter.published');

        if ($published == '') {
            $query->where('( cto.published = 1 OR cto.published = 0 )');
        } else if ($published != '*') {
            $published = (int) $published;
            $query->where("cto.published = '" . $published . "'");
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
            $field_searches = " cto.intIdContacto_cc LIKE '" . $search . "' OR"
                    . " cto.intIdCargo_cgo LIKE '" . $search . "' AND"
                    . " cgo.strDescripcion_cgo LIKE '" . $search . "' AND"
                    . " cto.intIdContratista_cta LIKE '" . $search . "' AND"
                    . " ctr.strContratista_cta LIKE '" . $search . "' AND"
                    . " cto.intIdPersona_pc LIKE '" . $search . "'";

            //  Adjuntamos via metodo where las opciones de busqueda registradas en al variable "$field_searches"
            $query->where($field_searches);
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