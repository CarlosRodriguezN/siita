<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<div class="width-60 fltlft" >
    <fieldset class="adminform">
        <legend>&nbsp; <?php echo JText::_( 'COM_POA_FIELD_LST_ACTIVIDADES' ) ?>&nbsp; </legend>
        <div class="fltrt">
            <input id="addAccionPln" type="button" value="&nbsp;<?php echo JText::_( 'BTN_AGREGAR' ) ?>&nbsp;">
        </div>
        <table id="tbLstPlanAccion" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"><?php echo JText::_( 'COM_POA_FIELD_PLAN_ACCION_DESCRIPCION_LABEL' ) ?></th>
                    <th align="center"><?php echo JText::_( 'COM_POA_FIELD_PLAN_ACCION_TIPO_ACCION_LABEL' ) ?></th>
                    <th align="center"><?php echo JText::_( 'COM_POA_FIELD_PLAN_ACCION_PRESUPUESTO_LABEL' ) ?></th>
                    <th align="center"><?php echo JText::_( 'COM_POA_FIELD_PLAN_ACCION_FECHA_EJECUCION_LABEL' ) ?></th>
                    <th colspan="2" align="center"><?php echo JText::_( 'ACCION' ) ?></th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </fieldset>
</div>

<div class="width-40 fltrt" >
    <div id="imgPlnAccion" style=" 
         background: url(images/logo_default.jpg);
         background-size: contain;
         background-repeat: no-repeat;
         background-position: center center;
         width: 100%;
         height: 213px;"
         >
    </div>
    <div id="frmPlnAccion" class ="hide" >

        <fieldset class="adminform">
            <legend> &nbsp;<?php echo JText::_( 'COM_POA_FIELD_DATA_GENERAL_LABEL' ) ?>&nbsp;</legend>
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'plnaccion' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <fieldset class="adminform">
                <legend> &nbsp;<?php echo JText::_( 'COM_POA_FIELD_FUNCIONARIO_LABEL' ) ?>&nbsp; </legend>
                <ul class="adminformlist">
                    <?php foreach( $this->form->getFieldset( 'funcionarioRes' ) as $field ): ?>
                        <li>
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </fieldset>
            <fieldset class="adminform">
                <legend> &nbsp;<?php echo JText::_( 'COM_POA_FIELD_UNIDAD_GESTION_LABEL' ) ?>&nbsp; </legend>
                <ul class="adminformlist">
                    <?php foreach( $this->form->getFieldset( 'unidadGestionRes' ) as $field ): ?>
                        <li>
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </fieldset>

            <div class="clr"></div>
            <input id="btnAddAccion" type="button" value="&nbsp;<?php echo JText::_( 'BTN_GUARDAR' ) ?>&nbsp;">
            <input id="btnCancelAccion" type="button" value="&nbsp;<?php echo JText::_( 'BTN_CANCELAR' ) ?>&nbsp;">
            <div class="clr"></div>

        </fieldset>

    </div>
</div>
