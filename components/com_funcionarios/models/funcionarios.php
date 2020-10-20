<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );
// import the Joomla modellist library
jimport( 'joomla.application.component.modellist' );
JTable::addIncludePath(JPATH_BASE . DS . 'components' . DS . 'com_funcionarios' . DS . 'tables');
require_once JPATH_BASE . DS . 'administrator' . DS .'components' . DS . 'com_unidadgestion' . DS . 'helpers' . DS . 'unidadgestion.php';

/**
 * HelloWorldList Model
 */
class FuncionariosModelFuncionarios extends JModelList
{

    public function __construct( $config = array( ) )
    {
        if( empty( $config['filter_fields'] ) ) {
            //  Creamos un arreglo por con los campos por los cuales vamos a realizar los filtros, 
            //  en este caso seria nombre, descripcion, estado( publicado / despublicado )
            $config['filter_fields'] = array(   'strCI_fnc',
                                                'strApellido_fnc',
                                                'strNombre_fnc',
                                                'strCorreoElectronico_fnc'
            );
        }

        parent::__construct( $config );
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
        foreach( $items as &$item ) {
            $item->url = JRoute::_( 'index.php?option=com_funcionarios&amp;task=funcionario.edit&amp;intIdUser_fnc='. $item->idUserFnc .'&amp;intCodigo_fnc=' . $item->idFuncionario );
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
        $idUsr = JFactory::getUser()->id;
        $canDo = FuncionariosHelper::getActions();
        
        //  Llama a la funcion getListQuery del padre en este caso del objeto JModelList, 
        //  el cual retorna un objeto que nos permite hacer una consulta a la BBDD        
        $query = parent::getListQuery();

        $query->select( '   fnc.intCodigo_fnc             AS idFuncionario,
                            fnc.intIdentidad_ent           AS isEntidadFnc,
                            fnc.intIdUser_fnc              AS idUserFnc,
                            fnc.strCI_fnc                  AS CIFnc,
                            fnc.strApellido_fnc            AS apellidosFnc,
                            fnc.strNombre_fnc              AS nombresFnc,
                            fnc.strCorreoElectronico_fnc   AS correoFnc,
                            fnc.strTelefono_fnc            AS telefonoFnc,
                            fnc.strCelular_fnc             AS celularFnc,
                            fnc.published                  AS publishedFnc,
                            fug.intId_ugf                  AS idFncUg,
                            fug.intCodigo_ug               AS idUg,
                            fug.published                  AS publishedFncUg' );
        $query->from( '#__gen_funcionario fnc' );
        $query->innerJoin('#__gen_ug_funcionario fug ON fug.intCodigo_fnc = fnc.intCodigo_fnc');
        //  Verificamos si algun registro esta siendo editado por algun usuario
        $query->leftJoin( '#__users as u on u.id = fnc.checked_out' );
        $query->where('fug.published = 1');
        $query->where('fnc.published = 1');

        if( !$canDo->get('core.create') && !$canDo->get('core.edit') && !$canDo->get('core.delete') ) {
            $query->where( "fnc.intIdUser_fnc = {$idUsr}" );
        }
        
        //
        //  Filtro por palabra o frase
        //
        //  Setea el valor de la variable de session "filter.search" 
        //  obtenida desde el metodo populateState
        $search = $this->getState( 'filter.search' );
        $db = $this->getDbo();

        if( !empty( $search ) ) {
            //  Prepara la cadena existente en la variable $search para que pueda ser ejecutada 
            //  en una sentencia Sql, con esto se previene de un ataque XSS
            $search = '%' . $db->getEscaped( $search, true ) . '%';

            $field_searches = " (fnc.strCI_fnc LIKE '" . $search . "' OR
                                fnc.strApellido_fnc LIKE '" . $search . "' OR 
                                fnc.strNombre_fnc LIKE '" . $search . "' OR 
                                fnc.strCorreoElectronico_fnc LIKE '" . $search . "')";

            //  Adjuntamos via metodo where las opciones de busqueda registradas en al variable "$field_searches"
            $query->where( $field_searches );
        }

        //
        //  Filtro para ordenar por columna seleccionada
        //
        $orderCol = $this->getState( 'list.ordering' );
        $orderDirn = $this->getState( 'list.direction' );

        if( $orderCol != '' ) {
            $query->order( $db->getEscaped( $orderCol . ' ' . $orderDirn ) );
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
    protected function populateState( $ordering = null, $direction = null )
    {
        //
        //  Crea la variable de session "context.filter.search", y setea el valor ingresado por el usuario 
        //  en la caja de texto llamada "filter_search", en caso de existir esta variable simplemente actualiza 
        //  el contenido
        //
        $search = $this->getUserStateFromRequest( $this->context . '.filter.search', 'filter_search' );
        $this->setState( 'filter.search', $search );

        $published = $this->getUserStateFromRequest( $this->context . '.filter.published', 'filter_published' );
        $this->setState( 'filter.published', $published );

        parent::populateState( $ordering, $direction );
    }


    public function getLstUG()
    {
        $tbUG = $this->getTable('UnidadGestion', 'FuncionariosTable', $config = array());
        return $tbUG->getLstUnidadesGestion();
    }
}