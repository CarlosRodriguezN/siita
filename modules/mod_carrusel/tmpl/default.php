<?php
defined('_JEXEC') or die;
//$document->addStyleSheet(JURI::root() . 'modules/mod_carrusel/tmpl/css/style.css');
$document->addStyleSheet(JURI::root() . 'modules/mod_carrusel/tmpl/css/jdefault-five-slides.css');
$document->addStyleSheet(JURI::root() . 'modules/mod_carrusel/tmpl/css/default.css');

$document->addScript(JURI::root() . 'modules/mod_carrusel/tmpl/js/css3-mediaqueries.js');
$document->addScript(JURI::root() . 'modules/mod_carrusel/tmpl/js/jquery.flexslider-min.js');
$document->addScript(JURI::root() . 'modules/mod_carrusel/tmpl/js/kwiks.js');
$document->addScript(JURI::root() . 'modules/mod_carrusel/tmpl/js/load.js');

?>


<div id="container" style="height: 350px">
    <div class="flexslider">
        <ul class="slides">
            <?php foreach ($lstProgramas AS $programa):?>
                <li>
                    <img src="<?php echo JPATH_SITE. '/images/stories/programa/imagenes/'. $programa->idPrograma . '.jpg'; ?>" width="500px" height="250px"/>
                    <div class="flex-caption">
                        <h3><?php echo substr($programa->strNombre, 0, 30) . '...'; ?></h3>
                        <p><?php echo substr($programa->descripcion, 0, 60) . '...'; ?>
                            <br><a href="?option=com_programa&view=programaview&idPrograma=<?php echo $programa->idPrograma; ?>&Itemid=110">Ver más</a>
                        </p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<span id="responsiveFlag"></span>