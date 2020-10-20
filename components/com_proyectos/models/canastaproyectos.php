<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

JTable::addIncludePath( JPATH_BASE . DS . 'components' . DS . 'com_proyectos' . DS . 'tables' );

/**
 * Etapas Model
 */
class ProyectosModelCanastaProyectos extends JModelList
{
    public function __construct( $config = array() ) 
    {
        if( empty( $config['filter_fields'] ) ){
            //  Creamos un arreglo por con los campos por los cuales vamos a realizar los filtros, 
            //  en este caso seria nombre, descripcion, estado( publicado / despublicado )
            $config['filter_fields'] = array(   'strDescripcion_cb',
                                                'published' );
        }
        
        parent::__construct( $config );
    }
    
    public function getItems()
    {
        //  Joomla ejecuta la funcion getItems de la clase JModelList
        //  la cual por defecto busca la funcion "getListQuery" y 
        //  retorna la informacion dentro de un objeto
        $items = parent::getItems();

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
        
        $query->select( '   t1.intIdPropuesta_cp AS idPropuesta,
                            t1.strNombre_cp AS nombre, 
                            t2.strNombre_ins AS institucion,
                            t1.intNumeroBeneficiarios AS numBeneficiarios,
                            t1.dcmMonto_cp AS monto,
                            t3.strDescripcion_estado AS prioridad,
                            t1.published,
                            u.name' );
        $query->from( '#__cp_propuesta t1' );
        $query->join( 'INNER', '#__gen_institucion t2 ON t1.intCodigo_ins = t2.intCodigo_ins' );
        $query->join( 'INNER', '#__gen_estados t3 ON t1.inpCodigo_estado = t3.inpCodigo_estado' );
        $query->where( 't1.intIdPropuesta_cp NOT IN (   SELECT ta.intIdPropuesta_cp 
                                                        FROM tb_cp_propuesta_proyecto ta )' );
        
        //  Verificamos si algun registro esta siendo editado por algun usuario
        $query->leftJoin( '#__users as u on u.id = t1.checked_out' );
        //
        //  Filtro estado del contenido ( publicado / despublicado )
        //
        //  Setea el valor de la variable de session "filter.search" 
        //  obtenida desde el metodo populateState
        //
        $published = $this->getState( 'filter.published' );
        
        if( $published == '' ){
            $query->where( '( t1.published = 1 OR t1.published = 0 )' );
        } else if( $published != '*' ){
            $published = (int)$published;
            $query->where( "t1.published = '". $published ."'" );
        }
        
        //
        //  Filtro por palabra o frase
        //
        //  Setea el valor de la variable de session "filter.search" 
        //  obtenida desde el metodo populateState
        $search = $this->getState( 'filter.search' );
        $db = $this->getDbo();
        
        if( !empty ( $search ) ){
            //  Prepara la cadena existente en la variable $search para que pueda ser ejecutada 
            //  en una sentencia Sql, con esto se previene de un ataque XSS
            $search = '%'. $db->getEscaped( $search, true ) .'%';

            $field_searches = " t1.strNombre_cp LIKE '". $search ."' OR
                                t1.strNombre_ins LIKE '". $search ."' OR
                                t1.strPrioridad LIKE '". $search ."'";
            
            //  Adjuntamos via metodo where las opciones de busqueda registradas en al variable "$field_searches"
            $query->where( $field_searches );
        }
        
        //
        //  Filtro para ordenar por columna seleccionada
        //
        $orderCol = $this->getState( 'list.ordering' );
        $orderDirn = $this->getState( 'list.direction' );
        
        if( $orderCol != '' ){
            $query->order( $db->getEscaped( $orderCol. ' ' .$orderDirn ) );
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
        $search = $this->getUserStateFromRequest( $this->context.'.filter.search', 'filter_search' );
        $this->setState( 'filter.search', $search );
        
        $published = $this->getUserStateFromRequest( $this->context.'.filter.published', 'filter_published' );
        $this->setState( 'filter.published', $published );
        
        parent::populateState( $ordering, $direction );
    }

    
    /**
     * 
     * Retorno lista de Programas registrados en el sistema SIITA
     * 
     * @return type
     */
    public function lstProgramas()
    {
        $tbPrograma = $this->getTable( 'Programa', 'ProyectosTable' );
        $lstProgramas = $tbPrograma->lstProgramas();
        
        $options = false;
        
        if( $lstProgramas ){
            
            foreach ( $lstProgramas as $programa )
            {
                $options[] = JHtml::_( 'select.option', 
                                        $programa->id, 
                                        $programa->nombre );
            }
            
            $options[] = JHtml::_( 'select.option', 
                                    0, 
                                    'SIN PROGRAMA' );
        }
        
        return $options;
    }
    
    
    /**
     * 
     * Identificador de un Proyecto
     *  
     * @param type $idPrograma  Identificador de un Programa
     * 
     */
    public function getLstProyectos( $idPrograma )
    {
        $tbProyecto = $this->getTable( 'Proyecto', 'ProyectosTable' );
        $rst = $tbProyecto->lstProyectos( $idPrograma );
        
        
        if( count( $rst ) > 0 ){
            $rst[] = (object)array( 'id' => 0, 'nombre' => JText::_( 'COM_PROYECTO_SELECCIONE_PROYECTO' ) );
        }else{
            $rst = (object)array( '0' => JText::_( 'COM_PROYECTO_SIN_REGISTROS' ) );
        }
        
        return $rst;
    }
}