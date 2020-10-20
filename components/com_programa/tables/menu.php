<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

//  Import Joomla JUser Library
jimport('joomla.user.user');

/**
 * 
 * Clase que gestiona informacion de la tabla Fase ( #__prf_etapa )
 * 
 */
class ProgramaTableMenu extends JTable {

    /**
     * Constructor
     *
     *   @param object Database connector object
     * 
     */
    function __construct(&$db) {
        parent::__construct('#__menu', 'id', $db);
    }

    /**
     * Gestiona el MENU de un PROGRAMA
     * @param int       $idPrograma      Identificador del programa.
     * @param object    $info            Data del programa
     * @return int                       Identificadoe del menu gestionado.
     */
    public function saveProgramaMenu($idPrograma, $info) {

        $rlMenu = $this->_getRgtLgtMenu($info->idMenu);
        $params = '{"show_title":"","link_titles":"","show_intro":"","show_category":"","link_category":"","show_parent_category":"","link_parent_category":"","show_author":"","link_author":"","show_create_date":"","show_modify_date":"","show_publish_date":"","show_item_navigation":"","show_vote":"","show_icons":"","show_print_icon":"","show_email_icon":"","show_hits":"","show_noauth":"","urls_position":"","menu-anchor_title":"","menu-anchor_css":"","menu_image":"","menu_text":1,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}';
        
        $data = array();
        $data["id"]                 = ($info->articlePrg->idMenu)   ? $info->articlePrg->idMenu 
                                                                    : 0;
        $data["menutype"]           = "mainmenu";
        $data["title"]              = $info->nombrePrg;
        $data["alias"]              = ( $info->alias ) ? $info->alias : $info->nombrePrg;
        $data["note"]               = "";
        $data["path"]               = "Búsqueda inteligente/Actualizar Joomla!/programas/dra/" . $idPrograma;
        $data["link"]               = "index.php?option=com_programa&view=programaview&idPrograma=" . $idPrograma;
        $data["type"]               = "component";
        $data["published"]          = $info->estadoPrg;
        $data["parent_id"]          = "108";
        $data["level"]              = "2";
        $data["component_id"]       = "22";
        $data["ordering"]           = "0";
        $data["checked_out"]        = "0";
        $data["checked_out_time"]   = "0000-00-00 00:00:00";
        $data["browserNav"]         = "0";
        $data["access"]             = "1";
        $data["img"]                = "";
        $data["template_style_id"]  = "0";
        $data["params"]             = $params;
        $data["lft"]                = $rlMenu["lft"];
        $data["rgt"]                = $rlMenu["rgt"];
        $data["home"]               = "0";
        $data["language"]           = "*";
        $data["client_id"]          = "0";

        if ( !$this->save($data) ) {
             echo $this->getError(); 
             exit;
        }
        
        return $this->id;
    }

    /**
     * Gestion la informacion de un menu para un "SUB PROGRAMA."
     * @param int       $idPrograma     Identificador del Porgrama
     * @param object    $subPrograma    Identificador del Sub Programa
     * @param int       $idMenu         Id del menu.
     * @param int       $idParent       Id del menu de el programa
     */
    public function saveSubProgramaMenu($subPrograma, $idSubPrograma, $idPrograma, $idMenuPrograma) {

        $rlMenu = $this->_getRgtLgtMenu($subPrograma->idMenu);

        $params = '{"show_title":"","link_titles":"","show_intro":"","show_category":"","link_category":"","show_parent_category":"","link_parent_category":"","show_author":"","link_author":"","show_create_date":"","show_modify_date":"","show_publish_date":"","show_item_navigation":"","show_vote":"","show_icons":"","show_print_icon":"","show_email_icon":"","show_hits":"","show_noauth":"","urls_position":"","menu-anchor_title":"","menu-anchor_css":"","menu_image":"","menu_text":1,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}';

        $data = array();

        $data["id"]                 = (int) $subPrograma->idMenu;
        $data["menutype"]           = "mainmenu";
        $data["title"]              = $subPrograma->descripcion;
        $data["alias"]              = ($subPrograma->alias) ? $subPrograma->alias : $subPrograma->descripcion;
        $data["note"]               = "";
        $data["path"]               = "Búsqueda inteligente/Actualizar Joomla!/programas/dra/" . $subPrograma->idSubPrograma;
        $data["link"]               = "index.php?option=com_programa&view=subprogramaview&idPrograma=" . $idPrograma . "&idSubPrograma=" . $idSubPrograma;
        $data["type"]               = "component";
        $data["published"]          = $subPrograma->published;
        $data["parent_id"]          = $idMenuPrograma;
        $data["level"]              = "3";
        $data["component_id"]       = "22";
        $data["ordering"]           = "0";
        $data["checked_out"]        = "0";
        $data["checked_out_time"]   = "0000-00-00 00:00:00";
        $data["browserNav"]         = "0";
        $data["access"]             = "1";
        $data["img"]                = "";
        $data["template_style_id"]  = "0";
        $data["params"]             = $params;
        $data["lft"]                = (int) $rlMenu["lft"];
        $data["rgt"]                = (int) $rlMenu["rgt"];
        $data["home"]               = "0";
        $data["language"]           = "*";
        $data["client_id"]          = "0";

        $idMenuSubPrograma = 0;

        if ($this->save($data)) {
            $idMenuSubPrograma = $this->id;
        }

        return $idMenuSubPrograma;
    }

    /**
     * Forma el RGT y RFT de un menu.
     * @param int $idMenu identidicador del menu.
     * @return type
     */
    private function _getRgtLgtMenu($idMenu) {
        $rlMenu = array();
        if ((int) $idMenu == 0) {
            $oldRgt = $this->_getLastRgt();
            $rlMenu["lft"] = $oldRgt + 1;
            $rlMenu["rgt"] = $oldRgt + 2;
        } else {
            $rlObjet = $this->_getLftRgt($idMenu);
            $rlMenu["lft"] = $rlObjet->lft;
            $rlMenu["rgt"] = $rlObjet->rgt;
        }

        return $rlMenu;
    }

    /**
     * recupera el ultimo RGT del menu tipo mainmenu
     * @return type
     */
    private function _getLastRgt() {
        try {
            $db = &JFactory::getDBO();
            $db->getQuery(true);
            $query = $db->getQuery(true);
            // Armo la sentencia SQL para INSERTAR los valores
            $query->select('max(rgt) AS max_rgt');
            $query->from('#__menu');
            $query->where('menutype LIKE "mainmenu"');
            //  Ejecución
            $db->setQuery($query);
            $db->query();

            return $db->loadObject()->max_rgt;
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_adminmapa.table.programa.log.php');
            $log->add($e->getMessage(), JLog:: ERROR, $e->getCode());
        }
    }

    /**
     * Recupera el RGT, el EFT de un menu 
     * @param int $idMenu   Identificador del menu.
     * @return type
     */
    private function _getLftRgt($idMenu) {
        try {
            $db = &JFactory::getDBO();
            $db->getQuery(true);
            $query = $db->getQuery(true);
            // Armo la sentencia SQL para INSERTAR los valores
            $query->select('lft,rgt');
            $query->from('#__menu');
            $query->where('id=' . $idMenu);
            //  Ejecución
            $db->setQuery($query);
            $db->query();

            return $db->loadObject();
        } catch (Exception $e) {
            jimport('joomla.error.log');
            $log = &JLog::getInstance('com_adminmapa.table.programa.log.php');
            $log->add($e->getMessage(), JLog:: ERROR, $e->getCode());
        }
    }

}