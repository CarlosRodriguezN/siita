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
class ProgramaTableContent extends JTable
{

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    public function __construct( &$db )
    {
        parent::__construct( '#__content', 'id', $db );
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
    public function registroProgramaContent( $idAssets, $dtaPrg )
    {
        $dtaPrograma["id"]          = $dtaPrg->articlePrg->idContent;
        $dtaPrograma["asset_id"]    = $idAssets;
        $dtaPrograma["parent_id"]   = '37';
        $dtaPrograma["title"]       = $dtaPrg->nombrePrg;
        $dtaPrograma["alias"]       = $dtaPrg->nombrePrg;

        $descripcion = ( $dtaPrg->descripcionPrg )  ? $dtaPrg->descripcionPrg
                                                    : JText::_( COM_PROGRAMA_SIN_DESCRIPCION );
        
        $dtaPrograma["introtext"]   = ' <p>
                                            <img src="'. $this->_getImagen( $dtaPrg->idPrg ) .'?ver='. rand() .'" alt="'. $dtaPrg->nombrePrg .'" style="display: block; margin-left: auto; margin-right: auto;" height="248" width="708" />
                                            <br>'. $descripcion .'
                                        </p>';

        $dtaPrograma["state"]       = '1';
        $dtaPrograma["sectionid"]   = '0';
        $dtaPrograma["mask"]        = '0';
        $dtaPrograma["catid"]       = '8';
        $dtaPrograma["created"]     = getdate();
        $dtaPrograma["images"]      = '{"image_intro":"","float_intro":"","image_intro_alt":"","image_intro_caption":"","image_fulltext":"","float_fulltext":"","image_fulltext_alt":"","image_fulltext_caption":""}';
        $dtaPrograma["url"]         = '{"urla":null,"urlatext":"","targeta":"","urlb":null,"urlbtext":"","targetb":"","urlc":null,"urlctext":"","targetc":""}';
        $dtaPrograma["attribs"]     = '{"show_title":"","link_titles":"","show_intro":"","show_category":"","link_category":"","show_parent_category":"","link_parent_category":"","show_author":"","link_author":"","show_create_date":"","show_modify_date":"","show_publish_date":"","show_item_navigation":"","show_icons":"","show_print_icon":"","show_email_icon":"","show_vote":"","show_hits":"","show_noauth":"","urls_position":"","alternative_readmore":"","article_layout":"","show_publishing_options":"","show_article_options":"","show_urls_images_backend":"","show_urls_images_frontend":""}';
        $dtaPrograma["version"]     = '6';
        $dtaPrograma["parentid"]    = '0';
        $dtaPrograma["ordering"]    = '';
        $dtaPrograma["access"]      = '1';
        $dtaPrograma["hits"]        = '81';
        $dtaPrograma["metadata"]    = '{"robots":"","author":"","rights":"","xreference":""}';
        $dtaPrograma["featured"]    = '0';
        $dtaPrograma["language"]    = '*';
        
        if( !$this->save( $dtaPrograma ) ){
            echo $this->getError(); 
            exit;
        }

        return $this->id;
    }

    
    private function _getImagen( $idFile )
    {
        $result = 'images/sinimagen.jpg';
        $path   = JPATH_BASE . DS . 'cache' . DS . 'lofthumbs'. DS . '708x248-'. $idFile;
        $pathImg= JURI::root() .'cache' . DS . 'lofthumbs'. DS;

        switch( true ){
            case ( file_exists( $path . ".png" ) ):
                $result = $pathImg. '708x248-' .$idFile . ".png";
            break;

            case ( file_exists( $path . ".jpeg" ) ):
                $result = $pathImg. '708x248-' .$idFile . ".jpeg";
            break;
        
            case ( file_exists( $path . ".jpg" ) ):
                $result = $pathImg. '708x248-' .$idFile . ".jpg";
            break;
        
            case ( file_exists( $path . ".gif" ) ):
                $result = $pathImg. '708x248-' .$idFile . ".gif";
            break;
        }

        return $result;
    }

    
    
}
