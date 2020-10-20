<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<div class="adminform width-100">
    <div class="width-60 fltrt">
        <?php echo $this->loadTemplate('mapa'); ?>
    </div>
    <div class="width-40 fltleft">
        <fieldset class="adminform">
            <legend><?php echo " " . $this->proyecto->nomProyecto . " " ?></legend>
            <div style="height: 400px"class="fltleft scroll">
                <p><?php echo $this->proyecto->strAlias_prg ?></p>
                <img alt="<?php echo $this->proyecto->nomProyecto ?>" 
                     src="<?php echo "components" . DS . "com_proyectos" . DS . "images" . DS . $this->proyecto->idProyecto . DS . "icon" . DS . $this->proyecto->idProyecto . ".jpg" ?>" style="margin-left: 10px; margin-right: 10px; float: left; width: 100px; height: 100px; ">
                <p>
                <table>
                    <tr>
                        <td><b><?php echo JText::_('COM_PROYECTO_VIEW_DATOSGRLS_FCHINICIO_LBL') ?></b></td>
                        <td><?php echo $this->proyecto->fchInicio ?></td>
                    </tr>
                    <tr>
                        <td><b><?php echo JText::_('COM_PROYECTO_VIEW_DATOSGRLS_FCHFIN_LBL') ?></b></td>
                        <td><?php echo $this->proyecto->fchFin ?></td>
                    </tr>
                    <tr>
                        <td><b><?php echo JText::_('COM_PROYECTO_VIEW_DATOSGRLS_FCHRESPONSABLE_LBL') ?></b></td>
                        <td><?php echo $this->proyecto->cargoResponsable . ".- " . $this->proyecto->responsable ?></td>
                    </tr>
                    <tr>
                        <td><b><?php echo JText::_('COM_PROYECTO_VIEW_DATOSGRLS_DURACION_LBL') ?></b></td>
                        <td><?php echo $this->proyecto->duracion . " - " . $this->proyecto->undMedida ?></td>
                    </tr>
                </table>

                </p>
                <p>
                    <?php echo $this->proyecto->descripcion ?>
                </p>
            </div>
        </fieldset>
    </div>
</div>