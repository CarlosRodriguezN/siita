<?php
    // No direct access to this file
    defined('_JEXEC') or die('Restricted Access');
?>

<div class="width-50 fltrt">
    <div id="imgAlineacion" class="editbackground"></div>
    <div id="frmAlineacion" class="hide">
        <fieldset class="adminform">
            <legend> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_ALINEACIONPROYECTO_TITLE' ) ?> </legend>
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'relacionProyecto' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="clr"></div>
            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                <input id="btnAddRelacion" type="button" value="<?php echo JText::_( 'BTN_GUARDAR' )?>">
            <?php endIf; ?>
            <input id="btnLimpiarRelacion" type="button" value="<?php echo JText::_( 'BTN_CANCELAR' )?>">
            <div class="clr"></div>
        </fieldset>
    </div>
</div>

<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend><?echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_RELACIONES_TITLE' )?></legend>
        <div class="fltrt">
            <input id="btnAdTableRelacion" type="button" value="<?php echo JText::_( 'BTN_AGREGAR' ) ?>">
        </div>
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
                <?php if( $this->lstMetasNacionalesProyecto ): ?>
                    <?php $x = 0; ?>
                    <?php foreach( $this->lstMetasNacionalesProyecto as $mnp ): ?>
                        <tr id="<?php echo++$x ?>">
                            <td align="center"> <?php echo $mnp->objNacional; ?> </td>
                            <td align="center"> <?php echo $mnp->politicaNacional; ?> </td>
                            <td align="center"> <?php echo $mnp->metaNacional; ?> </td>
                            
                            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                <td align="center"> <a class='updAlineacion'> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_UPDOTROSIND_LABEL' ); ?> </a> </td>
                                <td align="center"> <a class='delAlineacion'> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_DELOTROSIND_LABEL' ); ?> </td>
                            <?php else: ?>
                                <td align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_UPDOTROSIND_LABEL' ); ?> </td>
                                <td align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_DELOTROSIND_LABEL' ); ?> </td>                                
                            <?php endIf; ?>
                                
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>                                    
            </tbody>
        </table>
    </fieldset>
</div>