<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.table' );

/**
 * 
 * Clase que gestiona informacion de la tabla categoria ( #__categoria )
 * 
 */
class mantenimientoTableMakeTrigger extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct( &$db )
    {
        parent::__construct( '#__ut_dpa', 'inpID_dpa', $db );
    }

    /**
     *  Crea las tablas de losg de auditoria por compotente
     * 
     * @param array     $lstComAuditoria    lista de prefijos de componentes 
     *                                      a se auditados
     */
    public function crearTables( $lstComAuditoria )
    {
        $x = 0;
        foreach( $lstComAuditoria as $tabla ) {

            try {
                $db = & JFactory::getDbo();

                //  Elimino el trigger "SI" existe
                $sqlCreateTb = 'CREATE TABLE IF NOT EXISTS `tb_log_' . $tabla . '_audit` (
                                    `intId_log` int(11) NOT NULL AUTO_INCREMENT,
                                    `strTipoTrn` char(1) DEFAULT NULL,
                                    `strTabla` varchar(128) DEFAULT NULL,
                                    `intId` int(11) DEFAULT NULL,
                                    `strCampo` varchar(128) DEFAULT NULL,
                                    `strValorOriginal` varchar(1000) DEFAULT NULL,
                                    `strValorNuevo` varchar(1000) DEFAULT NULL,
                                    `dteFechaTrn` datetime DEFAULT NULL,
                                    `strUsuario` varchar(128) DEFAULT NULL,
                                    PRIMARY KEY (`intId_log`) );';
                $db->setQuery( $sqlCreateTb );
                if( $db->query() ) {
                    echo++$x . ': tb_log_' . $tabla . '_audit <hr>';
                }
            } catch(Exception $e) {
                jimport( 'joomla.error.log' );
                $log = &JLog::getInstance( 'com_mantenimiento.tables.dpa.log.php' );
                $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
                exit;
            }
        }
        exit;
    }

    /**
     *  Gestion la creacion y eliminacion de los triggers de auditoria
     * 
     * @param array     $lstComAuditoria    Lista de prefijos de los componentes
     */
    public function armarTrigger( $lstComAuditoria )
    {
        $x = 0;
        foreach( $lstComAuditoria AS $tbCom ) {
            $lstTablas = $this->_getLstTablas( $tbCom );
            foreach( $lstTablas as $tabla ) {
                $lstCampos = $this->_getCamposTabla( $tabla->TABLE_NAME );
                foreach( $lstCampos as $campo ) {
                    $columna = $campo->COLUMN_KEY;
                    if( $columna != null && $columna == 'PRI' ) {
                        $primeryKey = $campo->COLUMN_NAME;
                    }
                }

                $lstOpciones = array( "add", "upd", "del" );

                echo '<hr>' . ++$x . '<br>';
                //  Elimino el trigger "SI" existe
                $this->_dropTrigger( $tabla->TABLE_NAME, $lstOpciones );

                //  Creo el Trigger
                $this->_generarTrigger( $tabla->TABLE_NAME, $lstCampos, $lstOpciones, $tbCom, $primeryKey );
            }
        }
        exit;
    }

    /**
     *  Obtengo las Tablas de un componente
     * 
     * @param string    $tbCom  Prefijo de las tablas de un determinado componente
     * @return Object
     */
    private function _getLstTablas( $tbCom )
    {
        try {
            $db = & JFactory::getDbo();

            //  Selecciono las tablas de tipo general 
            //  pertenecientes al sistema siita - Ecorae
            $query = "  SELECT DISTINCT TABLE_NAME
                        FROM INFORMATION_SCHEMA.TABLES 
                        WHERE ( TABLE_NAME LIKE '" . $tbCom . "_%' ) AND TABLE_SCHEMA = 'dev_siita_tmp';";
            $db->setQuery( $query );

            $lstTables = $db->loadObjectList();
            return $lstTables;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_mantenimiento.tables.dpa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     * 
     * Selecciono los campos de un determinada tabla
     * 
     * @param type $tabla   tabla del sistema Siita - Ecore
     * 
     * @return type
     */
    private function _getCamposTabla( $tabla )
    {
        try {
            $db = & JFactory::getDbo();

            $query = "  SELECT COLUMN_NAME, COLUMN_KEY 
                        FROM INFORMATION_SCHEMA.COLUMNS
                        WHERE TABLE_SCHEMA = 'dev_siita_tmp' 
                        AND TABLE_NAME = '" . $tabla . "'";

            $db->setQuery( $query );
            $provincias = $db->loadObjectList();

            return $provincias;
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_mantenimiento.tables.dpa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     *  Elimina si existen los triggers de auditoria de una tabla
     * 
     * @param string    $nameTabla  Nombre de la tabla auditada
     * @param array     $lstOps     Lista de eventos a ser controlados (Insert, Delete y Update)
     */
    private function _dropTrigger( $nameTabla, $lstOps )
    {
        try {
            $db = & JFactory::getDbo();
            foreach( $lstOps As $op ) {
                $sqlDrop = 'DROP TRIGGER IF EXISTS `log_' . $op . '_' . $nameTabla . '`';
                $db->setQuery( $sqlDrop );
                $db->query(); 
                 
            }
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_mantenimiento.tables.dpa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     *  Crea los triggers de auditoria de una tabla a ser auditada
     * 
     * @param string    $nameTabla      Nombre la tabla
     * @param array     $lstCampos      Lista de campos de la tabla
     * @param array     $lstOps         Lista de eventos a controlar (Insert, Delete y Update)
     * @param string    $tbCom          Prefijo de las tablas del componente
     */
    private function _generarTrigger( $nameTabla, $lstCampos, $lstOps, $tbCom, $primeryKey )
    {
        try {
            $db = & JFactory::getDbo();
            foreach( $lstOps AS $op ) {
                $sqlTrigger = $this->_makeSQLTrigger( $nameTabla, $lstCampos, $op, $tbCom, $primeryKey );
                $db->setQuery( $sqlTrigger );
                echo $db->query(); 
                echo '--log_' . $op . '_' . $nameTabla . '<br>';
            }
        } catch(Exception $e) {
            jimport( 'joomla.error.log' );
            $log = &JLog::getInstance( 'com_mantenimiento.tables.dpa.log.php' );
            $log->add( $e->getMessage(), JLog::ERROR, $e->getCode() );
        }
    }

    /**
     *  Genera la sentencia sql para crear un trigger
     * 
     * @param string    $tabla      Nombre la tabla
     * @param array     $campos     Lista de campos de la tabla
     * @param string    $op         Evento a controlar (Insert, Delete y Update)
     * @param string    $tbCom      Prefijo de las tablas del componente
     * @return string
     */
    private function _makeSQLTrigger( $tabla, $campos, $op, $tbCom, $primeryKey )
    {
        $query = "  CREATE TRIGGER `log_" . $op . "_" . $tabla . "`";
        switch( $op ) {
            case "add":
                $query .= "         AFTER INSERT ON `" . $tabla . "`";
                break;
            case "upd":
                $query .= "         AFTER UPDATE ON `" . $tabla . "`";
                break;
            case "del":
                $query .= "         AFTER DELETE ON `" . $tabla . "`";
                break;
        }
        $query .= " FOR EACH ROW
                        BEGIN
                            INSERT INTO `tb_log_" . $tbCom . "_audit` (`intId_log`, `strTipoTrn`, `strTabla`, `intId`, `strCampo`, `strValorOriginal`, `strValorNuevo`, `dteFechaTrn`, `strUsuario`)
                            VALUES ";

        foreach( $campos as $campo ) {
            switch( $op ) {
                case "add":
                    $query .= "
                (0, 'I', '" . $tabla . "', NEW." . $primeryKey . ", '" . $campo->COLUMN_NAME . "', NULL, NEW." . $campo->COLUMN_NAME . ", NOW(),  NEW.checked_out),";
                    break;
                case "upd":
                    $query .= "
                (0, 'U', '" . $tabla . "', OLD." . $primeryKey . ", '" . $campo->COLUMN_NAME . "', OLD." . $campo->COLUMN_NAME . " , NEW." . $campo->COLUMN_NAME . ", NOW(),  NEW.checked_out),";
                    break;
                case "del":
                    $query .= "
                (0, 'D', '" . $tabla . "', OLD." . $primeryKey . ", '" . $campo->COLUMN_NAME . "', OLD." . $campo->COLUMN_NAME . ", NULL, NOW(),  OLD.checked_out),";
                    break;
            }
        }

        $query = rtrim( $query, ',' ) . ';';
        $query .= '     END';

        return $query;
    }

    public function reporte( $prefijoCom, $patronSerch )
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery( true );

        $prefijoCom = 'tb_pln';

        $query->select( 'strTipoTrn AS tipo de transaccion,
                            strTabla AS tabla,
                            strCampo AS campo,
                            strValorOriginal AS valor aterior,
                            strValorNuevo AS valor nuevo,
                            dteFechaTrn AS fecha,
                            strUsuario AS usuario' );
        $query->from( "tb_log_tb_cp'{$prefijoCom}'_audit" );
        $query->where( "intIdPropuesta_cp='{$patronSerch}'" );
        $db->setQuery( $query );
        $result = ($db->query()) ? true : false;

        return $result;
    }
    
}