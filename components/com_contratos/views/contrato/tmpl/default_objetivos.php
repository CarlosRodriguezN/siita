<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>
<div class="width-60 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_( 'COM_CONTRATOS_TITLE_LIST_OBJETIVOS_LABEL' ) ?> </legend>
        
        <?php if( $this->canDo->get( 'core.create' ) ): ?>
            <div class="fltrt">
                <input id="addObjetivoTable" type="button" value="&nbsp;<?php echo JText::_( 'BTN_AGREGAR_OBJ' ) ?> &nbsp;">
            </div>
        <?php endIf; ?>
        
        <table id="tbLstObjetivos" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"><?php echo JText::_( 'COM_CONTRATOS_FIELD_OBJETIVO_DESCRIPCION_LABEL' ) ?></th>
                    <th colspan="3" align="center"><?php echo JText::_( 'COM_CONTRATOS_FIELD_OBJETIVO_PLAN_ACCION_LABEL' ) ?></th>
                    <th colspan="2" align="center"><?php echo JText::_( 'TL_ACCIONES' ) ?></th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </fieldset>
</div>

<!--fomulario para registrar un nuevo objetivo del PROYECTO-->
<div class="width-40 fltrt" >
    <div id="imgObjetivo" class="editbackground">
    </div>
    <div id="frmObjetivo" class ="hide" >
        <fieldset class="adminform">
            <legend> <?php echo JText::_( 'COM_CONTRATOS_FIELD_DATA_GENERAL_LABEL' ) ?> </legend>
            <ul class="adminformlist" id="formObjCnt">
                <?php foreach( $this->form->getFieldset( 'objetivo' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div id="padre" class="hide">
                <input type="file" id="uploadFather" >
            </div>
            <div class="clr"></div>
            
            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                <input id="btnAddObj" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_GUARDAR' ) ?> &nbsp;">
            <?php endIf; ?>

            <input id="btnCancel" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_CANCELAR' ) ?> &nbsp;">
            <div class="clr"></div>
        </fieldset>
    </div>
</div>

