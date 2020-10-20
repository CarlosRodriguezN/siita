<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 * HelloWorldList Model
 */

class CanastaproyModelPropuestas extends JModelList {
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            //  Creamos un arreglo por con los campos por los cuales vamos a realizar los filtros, 
            //  en este caso seria nombre, descripcion, estado( publicado / despublicado )
            $config['filter_fields'] = array(   'intIdPropuesta_cp',
                                                'intIdentidad_ent',
                                                'inpCodigo_estado',
                                                'intCodigo_ins',
                                                'strNombre_cp',
                                                'strDescripcion_cp',
                                                'strCodigoPropuesta_cp',
                                                'strDocAdjunto_cp',
                                                'dcmMonto_cp',
                                                'intNumeroBeneficiarios',
                                                'dteFechaRegistro_cp',
                                                'dteFechaModificacion_cp',
                                                'published'
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
            $item->url = JRoute::_('index.php?option=com_canastaproy&amp;task=propuesta.edit&amp;intIdPropuesta_cp=' . $item->intIdPropuesta_cp);
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
        $query->select('   prop.intIdPropuesta_cp,     
                           prop.inpCodigo_estado,        
                           std.strDescripcion_estado, 
                           prop.intIdentidad_ent, 
                           prop.intCodigo_ins,           
                           ins.strNombre_ins,            
                           prop.strNombre_cp,            
                           prop.strCodigoPropuesta_cp,   
                           prop.strDocAdjunto_cp,        
                           prop.dcmMonto_cp,             
                           prop.intNumeroBeneficiarios,  
                           prop.dteFechaRegistro_cp,     
                           prop.dteFechaModificacion_cp, 
                           prop.strDescripcion_cp,       
                           prop.published,               
                           u.name');
        $query->from('#__cp_propuesta as prop');

        //  Verificamos si algun registro esta siendo editado por algun usuario
        $query->leftJoin('#__gen_estados as std on std.inpCodigo_estado = prop.inpCodigo_estado');
        $query->leftJoin('#__gen_institucion as ins on ins.intCodigo_ins = prop.intCodigo_ins');
        $query->leftJoin('#__users as u on u.id = prop.checked_out');
        
        //
        //  Filtro estado del contenido ( publicado / despublicado )
        //
        //  Setea el valor de la variable de session "filter.search" 
        //  obtenida desde el metodo populateState
        //
        $published = $this->getState('filter.published');

        if ($published == '') {
//            $query->where('( prop.published = 1 OR prop.published = 0 )');
            $query->where('( prop.published = 1 )');
        } else if ($published != '*') {
            $published = (int) $published;
            $query->where("prop.published = '" . $published . "'");
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

            $field_searches = " prop.strNombre_cp LIKE '" . $search . "' OR"
                            . " std.strDescripcion_estado LIKE '" . $search . "' OR"
                            . " ins.strNombre_ins LIKE '" . $search . "'";

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