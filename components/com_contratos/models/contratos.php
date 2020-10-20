<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 * HelloWorldList Model
 */
class contratosModelContratos extends JModelList
{

    private $_tpoContrato;
    
    public function __construct($config = array())
    {
        $this->_tpoContrato = ( (int)JRequest::getVar( 'tpoContrato' ) == 0 )   ? 1 
                                                                                : 2;

        if( empty($config['filter_fields']) ){
            //  Creamos un arreglo por con los campos por los cuales vamos a realizar los filtros, 
            //  en este caso seria nombre, descripcion, estado( publicado / despublicado )
            $config['filter_fields'] = array( 'intIdContrato_ctr',
                'strNombre',
                'strDescripcion'
            );
        }

        parent::__construct($config);
    }

    public function getItems()
    {
        //  Joomla ejecuta la funcion getItems de la clase JModelList
        //  la cual por defecto busca la funcion "getListQuery" y 
        //  retorna la informacion dentro de un objeto
        $items = parent::getItems();
        //  Recorro dicho objeto y a la propiedad "url" le asigno la url 
        //  de edicion de cada elemento de este conjunto de resultados
        //  Lo hacemos de esta manera para no hacerlo en la vista, 
        //  con esto mantenemos a nuestra vista ordenada
        foreach( $items as &$item ){
            $item->url = JRoute::_('index.php?option=com_contratos&amp;tpoContrato='. $this->_tpoContrato .'&amp;task=contrato.edit&amp;intIdContrato_ctr=' . $item->intIdContrato_ctr);
        }

        return $items;
    }

    /**
     *
     * Retorno informacion de la tabla "fases".
     * 
     * @return Object 
     */
    public function getListQuery()
    {
        //  Llama a la funcion getListQuery del padre en este caso del objeto JModelList, 
        //  el cual retorna un objeto que nos permite hacer una consulta a la BBDD        
        $query = parent::getListQuery();

        $query->select('    ctr.intIdContrato_ctr       AS idCtr,
                            ctr.intIdContrato_ctr       AS intIdContrato_ctr,
                            ctr.intCodigo_pry           AS codigoPryCtr,
                            pry.strNombre_pry           AS nombrePryCtr,
                            ctr.intIdTipoContrato_tc    AS codTpContratoCtr,
                            tc.strNombre_tc             AS nmbTipContatoCtr ,
                            ctr.intIdPartida_pda        AS codPartidaCtr,
                            prt.strDescripcion_pda      AS descPartidaCtr,
                            ctr.strCodigoContrato_ctr   AS codigoCtr,
                            ctr.strCUR_ctr              AS curCtr,
                            ctr.intPlazo_ctr            AS plazoCtr,
                            ctr.dcmMonto_ctr            AS montoCtr,
                            ctr.intNumContrato_ctr      AS numeroCtr,
                            ctr.strDescripcion_ctr      AS descripcionCtr,
                            ctr.strObservacion_ctr      AS observacionCtr, 
                            ctr.published');

        $query->from('#__ctr_contrato AS ctr');

        // Proyectos
        $query->leftJoin('#__pfr_proyecto_frm AS pry on pry.intCodigo_pry = ctr.intCodigo_pry');

        // Tipo de contrato
        $query->leftJoin('#__ctr_tipo_contrato AS tc on tc.intIdTipoContrato_tc = ctr.intIdTipoContrato_tc');

        // Partida
        $query->leftJoin('#__ctr_partidas AS prt on prt.intIdPartida_pda = ctr.intIdPartida_pda');
        
        //  Unidad de gestion responsable 
        $query->leftJoin('#__ctr_ug_responsable t5 ON ctr.intIdContrato_ctr = t5.intIdContrato_ctr');

        //  Filtro por tipo de contrato -> 1: tipo de Contrato
        $query->where( 'ctr.intIdTipoContrato_tc = ' . $this->_tpoContrato );
        
        if( JFactory::getUser()->id != 147 ){
            $query->where( 't5.intCodigo_ug IN (    SELECT DISTINCT tb.intCodigo_ug
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

        if( $published == '' ){
            $query->where(' ctr.published = 1 ');
        } else if( $published != '*' ){
            $published = (int)$published;
            $query->where("ctr.published = '" . $published . "'");
        }

        //
        //  Filtro por palabra o frase
        //
        //  Setea el valor de la variable de session "filter.search" 
        //  obtenida desde el metodo populateState
        $search = $this->getState('filter.search');
        $db = $this->getDbo();

        if( !empty($search) ){
            //  Prepara la cadena existente en la variable $search para que pueda ser ejecutada 
            //  en una sentencia Sql, con esto se previene de un ataque XSS
            $search = '%' . $db->getEscaped($search, true) . '%';

            $field_searches = " ctr.strCUR_ctr LIKE '" . $search . "' OR"
                    . " ctr.strDescripcion_ctr LIKE '" . $search . "' OR"
                    . " ctr.dcmMonto_ctr LIKE '" . $search . "' AND"
                    . " ctr.intIdTipoContrato_tc = 1";
            //  Adjuntamos via metodo where las opciones de busqueda registradas en al variable "$field_searches"
            $query->where($field_searches);
        }

        //
        //  Filtro para ordenar por columna seleccionada
        //
        $orderCol = $this->getState('list.ordering');
        $orderDirn = $this->getState('list.direction');

        if( $orderCol != '' ){
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
    protected function populateState($ordering = null, $direction = null)
    {
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
