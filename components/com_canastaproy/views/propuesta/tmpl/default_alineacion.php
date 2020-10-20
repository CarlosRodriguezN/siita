<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<!--lista de alineaciones al plan nacional para el buen vivir de una propuestas--> 
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend> Alineaciones Registradas </legend>
        <?php if( $this->canDo->get( 'core.create' ) ): ?>
            <div class="fltrt">
                <input id="addAlineacionesTable" type="button" value="<?php echo JText::_('BTN_AGREGAR') ?>">
            </div>
        <?php endIf; ?>
        
        <table id="tbLstAlineacion" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center">Objetivo Nacional</th>
                    <th align="center">Politica Nacional</th>
                    <th align="center">Meta Nacional</th>
                    <th colspan="2" align="center">Operacion</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($this->alineacionPropuesta): ?>
                    <?php $x = 0; ?>
                    <?php foreach ($this->alineacionPropuesta as $key => $mnp): ?>

                        <tr id="<?php $idReg = ++$key;
                                      echo $idReg; ?>">
                            <td align="center"> <?php echo $mnp->objNacional; ?> </td>
                            <td align="center"> <?php echo $mnp->politicaNacional; ?> </td>
                            <td align="center"> <?php echo $mnp->metaNacional; ?> </td>
                            
                            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                <td align="center" width="15" > <a id = "updPNBV-<?php echo $idReg ?>" class='updAlineacion'> <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_UPD_ALINIACION_LABEL'); ?> </a> </td>
                                <td align="center" width="15" > <a id = "delPNBV-<?php echo $idReg ?>" class='delAlineacion'> <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_DEL_ALINIACION_LABEL'); ?> </td>
                            <?php else: ?>
                                <td align="center" width="15" > <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_UPD_ALINIACION_LABEL'); ?> </td>
                                <td align="center" width="15" > <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_DEL_ALINIACION_LABEL'); ?> </td>
                            <?php endIf; ?>
                        </tr>

                    <?php endforeach; ?>
                <?php endif; ?>                                    

            </tbody>
        </table>
    </fieldset>
</div>

<!--fomulario para registrar una nueva alineacion-->
<div class="width-50 fltrt">
    <div id="imgAlineacionPNBV" style=" 
         background: url(images/logo_default.jpg);
         background-size: contain;
         background-repeat: no-repeat;
         background-position: center center;
         width: 100%;
         height: 213px;"
         >
        
    </div>
    <div id="frmAlineacionPNBV" class ="hide" >
        <fieldset class="adminform">
            <legend> <?php echo JText::_('COM_CANASTAPROY_FIELD_ALINEACION_PROYECTO_CP_TITLE') ?> </legend>
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('alineacionProyecto') as $field): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="clr"></div>
            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                <input id="btnAddRelacion" type="button" value="<?php echo JText::_('BTN_GUARDAR') ?>">
            <?php endIf; ?>
                
            <input id="btnLimpiarRelacion" type="button" value="<?php echo JText::_('BTN_LIMPIAR') ?>">
            <div class="clr"></div>
        </fieldset>
    </div>
</div>

