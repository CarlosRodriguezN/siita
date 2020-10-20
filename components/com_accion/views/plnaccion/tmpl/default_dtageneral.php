<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<div class="width-60 fltlft" >
    <fieldset class="adminform">
        <legend>&nbsp; <?php echo JText::_( 'COM_ACCION_FIELD_LST_ACTIVIDADES' ) ?>&nbsp; </legend>
        <div class="fltrt">
            <input id="addAccionPln" type="button" value="&nbsp;<?php echo JText::_( 'BTN_AGREGAR' ) ?>&nbsp;">
        </div>
        <table id="tbLstPlanAccion" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"><?php echo JText::_( 'COM_ACCION_FIELD_PLAN_ACCION_DESCRIPCION_LABEL' ) ?></th>
                    <th align="center"><?php echo JText::_( 'COM_ACCION_FIELD_PLAN_ACCION_TIPO_ACCION_LABEL' ) ?></th>
                    <th align="center"><?php echo JText::_( 'COM_ACCION_FIELD_PLAN_ACCION_PRESUPUESTO_LABEL' ) ?></th>
                    <th align="center"><?php echo JText::_( 'COM_ACCION_FIELD_PLAN_ACCION_FECHA_EJECUCION_LABEL' ) ?></th>
                    <th align="center"><?php echo JText::_( 'COM_ACCION_FIELD_PLAN_ACCION_FECHA_FIN_LABEL' ) ?></th>
                    <th colspan="2" align="center"><?php echo JText::_( 'ACCIONES' ) ?></th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </fieldset>
</div>

<div class="width-40 fltrt" >
    <div id="imgPlnAccion" class="editbackground">
    </div>
    <div id="frmPlnAccion" class ="hide" >
            <!-- Div/Tab de Acciones -->
            <div id="tabsAcciones" style="position: static; left: 20px; height: auto; width: 100%">
                <ul>
                    <li><a href="#accDataGeneral"> <?php echo JText::_('COM_ACCION_FIELD_ACCION_LABEL') ?></a></li>
                    <li><a href="#accResponsables"> <?php echo JText::_('COM_ACCION_FIELD_RESPONSABLES_LABEL') ?></a></li>
                </ul>

                <!-- Panel de control de una unidad de gestion -->
                <div class="m" id="accDataGeneral" style="padding: 0px" >
                    <fieldset class="adminform" style=" padding-right: 0px; padding-left: 10px;">
                        <legend> &nbsp;<?php echo JText::_( 'DATA_GENERAL' ) ?>&nbsp;</legend>
                        <ul class="adminformlist">
                            <?php foreach( $this->form->getFieldset( 'plnaccion' ) as $field ): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </fieldset>
                </div>

                <!-- Panel de control de una unidad de gestion -->
                <div class="m" id="accResponsables" style="padding: 0px">
                    <fieldset class="adminform" style=" padding-right: 0px; padding-left: 10px;" >
                        <legend> &nbsp;<?php echo JText::_( 'COM_ACCION_FIELD_FUNCIONARIO_LABEL' ) ?>&nbsp; </legend>
                        <ul class="adminformlist">
                            <?php foreach( $this->form->getFieldset( 'funcionarioRes' ) as $field ): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </fieldset>
                    <fieldset class="adminform" style=" padding-right: 0px; padding-left: 10px;">
                        <legend> &nbsp;<?php echo JText::_( 'COM_ACCION_FIELD_UNIDAD_GESTION_LABEL' ) ?>&nbsp; </legend>
                        <ul class="adminformlist">
                            <?php foreach( $this->form->getFieldset( 'unidadGestionRes' ) as $field ): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </fieldset>
                </div>
            <div class="clr"></div>
            <div style="margin-left: 10px;">
                <input id="btnAddAccion" type="button" value="&nbsp;<?php echo JText::_( 'BTN_GUARDAR' ) ?>&nbsp;">
                <input id="btnCancelAccion" type="button" value="&nbsp;<?php echo JText::_( 'BTN_CANCELAR' ) ?>&nbsp;">
                <input id="btnCerrar" type="button" style="display: none;" value="&nbsp;<?php echo JText::_( 'BTN_CERRAR' ) ?>&nbsp;">
            </div>
            <div class="clr"></div>
            </div>
    </div>
</div>
