<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport( 'joomla.database.table' );

//  Import Joomla JUser Library
jimport( 'joomla.user.user' );

/**
 * 
 * Gestiona el registro de la informacion de un programa en la 
 * tabla assets la cual forma parte del CMS - Joomla
 * 
 */
class ProgramaTableAssets extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    public function __construct( &$db )
    {
        parent::__construct( '#__assets', 'id', $db );
    }

    /**
     * 
     * Registro el programa en la tabla assets
     * 
     * @param int $idPrograma           Identificador del Programa
     * @param String $nombrePrograma    Nombre del programa
     * 
     * @return int      Identificador del registro del programa en la tabla assets
     * 
     */
    public function registroProgramaAssets( $idPrograma, $dtaPrograma )
    {
        $dtaPrg["id"]          = $dtaPrograma->articlePrg->idAssets;
        $dtaPrg["parent_id"]   = '37';
        $dtaPrg["level"]       = '';
        $dtaPrg["name"]        = 'com_content.article.prg.'. $idPrograma;
        $dtaPrg["title"]       = $dtaPrograma->nombrePrg;
        $dtaPrg["rules"]       = '{"core.admin":{"7":1},"core.manage":{"6":1}}';
        
        if( !$this->save( $dtaPrg ) ){
            echo $this->getError(); 
            exit;
        }

        return $this->id;
    }

}
