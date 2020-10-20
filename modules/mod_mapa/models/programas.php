<?php

define( 'DS', DIRECTORY_SEPARATOR );
define( 'JPATH_BASE', $_SERVER['DOCUMENT_ROOT'] );
require_once JPATH_BASE . DS . 'includes' . DS . 'ecoraeFramework.php';

defined( '_JEXEC' ) or die();

require_once JPATH_BASE . DS . 'libraries' . DS . 'ecorae' . DS . 'database' . DS . 'table' . DS . 'programas.php';

class programasModel extends JModel
{

    function getProgramas( $id, $tut )
    {
        $db = JFactory::getDbo();
        // creamos un objeto la la tabla progrmas  /libraries/ecorae/database/table/programas.php
        $tbProgramas = new JTableProgramas( $db );

        //llamamos al metodo getProgramas, le pasamos el id=unidad terrirotial, 
        //tut=tipo unidad territorial
        $programas = $tbProgramas->getProgramas( $id, $tut );

        return ( array ) $programas;
    }

    function getProgramasToJSON( $id, $tut )
    {
        $document = JFactory::getDocument();
        $document->setMimeEncoding( 'application/json' );
        echo json_encode( $this->getProgramas( $id, $tut ) );
    }

    function getProgramasToJSON2( $id, $tut )
    {// creada para cargar la primera vez
        return json_encode( array_values( $this->getProgramas( $id, $tut ) ) );
    }

}

if( ($_GET["tut"]) && ($_GET["task"]) && ($_GET["id"]) ){
    $tut = $_GET["tut"]; //id del tipo de las  unidades territoriales;
    $task = $_GET["task"]; //;
    $id = $_GET["id"]; //id del padre de las unidades territoriales ;

    $model = new programasModel();
    switch( $task ){
        case 'getProgramas':

            $model->getProgramasToJSON( $id, $tut );
            break;
    }
}

