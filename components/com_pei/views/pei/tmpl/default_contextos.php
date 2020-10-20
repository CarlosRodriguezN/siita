<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>
<div class="width-60 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_( 'COM_PEI_FIELD_LISTA_CONTEXTOS_DESC' ); ?> </legend>
        <?php if( $this->canDo->get( 'core.create' ) ): ?>        
            <div class="fltrt">
                <input id="addContexto" type="button" value=" &nbsp;<?php echo JText::_( 'COM_PEI_FIELD_ADD_NUEVO_CONTEXTOS_DESC' ) ?> &nbsp;">
            </div>
        <?php endIf; ?>
        
        <table id="tbLstContextos" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"><?php echo JText::_( 'COM_PEI_FIELD_CONTEXTO_NOMBRE_LABEL' ) ?></th>
                    <th align="center"><?php echo JText::_( 'COM_PEI_FIELD_CONTEXTO_DESCRIPCION_LABEL' ) ?></th>
                    <th colspan="4" align="center"><?php echo JText::_( 'ACCIONES' ) ?></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
</div>

<!--fomulario para registrar un nuevo objetivo del PEI-->
<div class="width-40 fltrt" >
    
    <div id="imgContexto" class="editbackground"></div>
    <div id="frmContexto" class="hide">
        <fieldset class="adminform">
            <legend> <?php echo JText::_( 'COM_PEI_FIELD_DTA_GRAL_CONTEXTOS' ) ?> </legend>
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'dtaContextos' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clr"></div>
            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                <input id="btnAddContexto" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_GUARDAR' ) ?> &nbsp;">
            <?php endIf; ?>
            <input id="btnCancelContexto" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_CANCELAR' ) ?> &nbsp;">
            <div class="clr"></div>
        </fieldset>
    </div>
</div>