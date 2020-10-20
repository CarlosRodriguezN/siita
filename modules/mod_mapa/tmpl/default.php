<?php
defined( '_JEXEC' ) or die;

// Hoja de estilos para pestañas - tabs
$document->addStyleSheet( JURI::root() . 'media/system/css/jquery-ui-1.8.13.custom.css' );

$document->addStyleSheet( 'modules/mod_mapa/tmpl/css/infowindows.css' );
$document->addStyleSheet( 'modules/mod_mapa/tmpl/css/modulo.css' );
$document->addStyleSheet( 'modules/mod_mapa/tmpl/css/lightbox.css' );
$document->addStyleSheet( 'modules/mod_mapa/tmpl/css/ie7/skin.css' );

//  Hoja de estilos para alertas
$document->addStyleSheet( JURI::root() . 'media/system/css/alerts/jquery.alerts.css' );

//  Hoja de estilos para tablas
$document->addStyleSheet(JURI::root() . 'media/system/css/tablesorter/jquery-tablesorter-style.css');

//  Adjunto script JQuery al sitio
if( !modMapaGoogleMaps::yaCargado() ){
    $document->addScript( 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places,drawing,geometry' );
}

$document->addScript( JURI::root() . 'media/system/js/jquery-1.7.1.min.js' );

//  Adjunto libreria que permite el trabajo de Mootools y Jquery
$document->addScript( JURI::root() . 'media/system/js/jquery-noconflict.js' );

$document->addScript( JURI::root() . 'media/system/js/jquery-ui-1.8.13.custom.min.js' );

$document->addScript( JURI::root() . 'media/mod_mapa/js/datum.js' );
$document->addScript( JURI::root() . 'media/system/js/alerts/jquery.alerts.js' );

$document->addScript( JURI::root() . 'media/mod_mapa/js/mif.tree/core/mif.tree.js' );
$document->addScript( JURI::root() . 'media/mod_mapa/js/mif.tree/core/mif.tree.draw.js' );
$document->addScript( JURI::root() . 'media/mod_mapa/js/mif.tree/core/mif.tree.hover.js' );
$document->addScript( JURI::root() . 'media/mod_mapa/js/mif.tree/core/mif.tree.load.js' );
$document->addScript( JURI::root() . 'media/mod_mapa/js/mif.tree/core/mif.tree.node.js' );
$document->addScript( JURI::root() . 'media/mod_mapa/js/mif.tree/core/mif.tree.selection.js' );

$document->addScript( JURI::root() . 'media/mod_mapa/js/mif.tree/more/mif.tree.checkbox.js' );
$document->addScript( JURI::root() . 'media/mod_mapa/js/mif.tree/more/mif.tree.cookiestorage.js' );
$document->addScript( JURI::root() . 'media/mod_mapa/js/mif.tree/more/mif.tree.drag.element.js' );
$document->addScript( JURI::root() . 'media/mod_mapa/js/mif.tree/more/mif.tree.drag.js' );
$document->addScript( JURI::root() . 'media/mod_mapa/js/mif.tree/more/mif.tree.keynav.js' );
$document->addScript( JURI::root() . 'media/mod_mapa/js/mif.tree/more/mif.tree.rename.js' );
$document->addScript( JURI::root() . 'media/mod_mapa/js/mif.tree/more/mif.tree.sort.js' );
$document->addScript( JURI::root() . 'media/mod_mapa/js/mif.tree/more/mif.tree.transform.js' );

$document->addScript( JURI::root() . 'modules/mod_mapa/tmpl/js/treeProyectos.js' );
$document->addScript( JURI::root() . 'modules/mod_mapa/tmpl/js/combosVinculantes.js' );
$document->addScript( JURI::root() . 'modules/mod_mapa/tmpl/js/mapaJs.js' );
$document->addScript( JURI::root() . 'modules/mod_mapa/tmpl/js/charts.js' );
$document->addScript( JURI::root() . 'modules/mod_mapa/tmpl/js/treeWMS.js' );
$document->addScript( JURI::root() . 'modules/mod_mapa/tmpl/js/wmsMap.js' );
$document->addScript( JURI::root() . 'modules/mod_mapa/tmpl/js/tabber.js' );
$document->addScript( JURI::root() . 'modules/mod_mapa/tmpl/js/lightbox.js' );
$document->addScript( JURI::root() . 'modules/mod_mapa/tmpl/js/jquery.jcarousel.min.js' );

$document->addStyleSheet( JURI::root() . 'modules/mod_mapa/tmpl/css/tree.css' );

$document->addScriptDeclaration( toJsonProgramas( $ltsProgramas ), $type = 'text/javascript' );
$document->addScriptDeclaration( toJsonWms( $ltsWMSLayers ), $type = 'text/javascript' );

?>

<dl class="mapa-module<?php echo $moduleclass_sfx ?>">
    <div>
        <div id="accordion" class="inMap">
            <h3><a href="#"><div id="lblProyectos">Programas</div></a></h3>
            <div class="contPrograma" style="padding: 0;">
                <div id="treeProyectos" ></div>
                <div style="padding:5px">
                    <div>Filtros</div>
                    <?php   $options = array();
                            if( count( $list ) > 0 ) {
                                foreach( $list AS $item ) {
                                    $options[] = JHTML::_( 'select.option', $item->id, $item->nombre );
                                };

                                echo JHTML::_( 'select.genericlist', $options, 'cbFiltro' );
                            } 
                    ?>

                    <div class="clr"></div>

                    <div id="lblEntidad">Region</div>

                    <select id ="cbZona" class="cbVinculantes" onchange="cbAddProvincias()">
                        <?php
                        echo '<option value="' . 0 . '"> Regiones</option>';
                        $numProvincias = count( $listRegiones );
                        if( $numProvincias > 0 ) {
                            for( $x = 0; $x < $numProvincias; $x++ ) {
                                echo '<option value="' . $listRegiones[$x]->id . '">' . $listRegiones [$x]->nombre . '</option>';
                            }
                        }
                        ?> 
                    </select>

                    <br>Provincias<br>

                    <select id = "cbProvincias" class="cbVinculantes" onchange="cbAddCantones()">
                        <?php
                        $numProvincias = count( $listProvincias );
                        echo '<option value="' . 0 . '"> Provincias</option>';
                        if( $numProvincias > 0 ) {
                            for( $x = 1; $x < $numProvincias; $x++ ) {

                                echo '<option value="' . $listProvincias[$x]->id . '">' . $listProvincias [$x]->nombre . '</option>';
                            }
                        }
                        ?> 
                    </select>

                    <br>Cantón<br>

                    <select id = cbCantones class="cbVinculantes" onchange="cbAddParroquias()">
                        <option value = "0">Cantón</option>

                    </select>
                    <br>Parroquia<br>
                    <select id = cbParroquias class="cbVinculantes" onchange="cbChangeParroquias()">
                        <option value = "0">Parroquias</option>
                    </select>
                </div>
            </div>
        </div>
        <div id="accordionMapas" class="inMap">
            <h3><a href="#"><div id="hrefMapas">Mapas</div></a></h3>
            <div  style="padding: 0">
                <div id="treeWMS" ></div>
            </div>
        </div>
</dl>