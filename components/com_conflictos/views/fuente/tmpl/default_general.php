<?php
    // No direct access to this file
    defined('_JEXEC') or die('Restricted Access');
?>

<div class="width-100 fltlft">
    <div class="width-50 fltlft">
        <fieldset class="adminform">
            <legend>&nbsp;<?php echo JText::_( "COM_CONFLICTOS_TAB_GENERAL_TITLE" ) ?>&nbsp;</legend>
            <!-- DATOS GENERALES. -->
            <ul class="adminformlist">
                <?php  if ( $this->admin ): ?>
                    <?php foreach( $this->form->getFieldset( 'formTema' ) as $field ): ?>
                        <?php $op = substr($field->id,-3, 3);?>
                        <?php  if ( $op == "Txt" ): ?>
                            <li id="li-<?php echo $field->id; ?>" style="display: none;">
                                <?php echo $field->label; ?>
                                <?php echo $field->input; ?>
                                
                                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                    <span id="saveTpoFnt" >
                                        <a href="#" id="saveTF" >
                                            <img src="/media/system/images/mantenimiento/save.png" title="<?php echo JText::_( "BTN_GUARDAR" ) ?>">
                                        </a>
                                    </span>
                                    <span id="cancelTpoFnt" >
                                        <a href="#" id="cancelTF" >
                                            <img src="/media/system/images/mantenimiento/close.png" title="<?php echo JText::_( "BTN_CANCELAR" ) ?>">
                                        </a>
                                    </span>
                                <?php endIf; ?>

                            </li>
                        <?php elseif ($field->type == "tiposfuente"): ?>
                            <li id="li-<?php echo $field->id; ?>">
                                <?php echo $field->label; ?>
                                <?php echo $field->input; ?>
                                
                                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                    <span id="gestionTpoFnt" >
                                        <span id="addTpoFnt" >
                                            <a href="#" id="addTF" >
                                                <img src="/media/system/images/mantenimiento/new.png" title="<?php echo JText::_( "BTN_NUEVO" ) ?>">
                                            </a>
                                        </span>
                                        <span id="updTpoFnt" >
                                            <a href="#" id="updTF" >
                                                <img src="/media/system/images/mantenimiento/edit.png" title="<?php echo JText::_( "BTN_EDITAR" ) ?>">
                                            </a>
                                        </span>
                                        <span id="delTpoFnt" >
                                            <a href="#" id="delTF" >
                                                <img src="/media/system/images/mantenimiento/delete.png" title="<?php echo JText::_( "BTN_ELIMINAR" ) ?>">
                                            </a>
                                        </span>
                                    </span>
                                <?php endif;?>
                            </li>
                        <?php else: ?>
                            <li>
                                <?php echo $field->label; ?>
                                <?php echo $field->input; ?>
                            </li>
                       <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php foreach( $this->form->getFieldset( 'formTema' ) as $field ): ?>
                        <li>
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                        </li>
                    <?php endforeach; ?>    
                <?php endif; ?>
            </ul>
        </fieldset>
    </div>
    <!-- UNIDAD TERRITORIAL -->
    <div class="width-50 fltrt">
        <fieldset class="adminform">
            <legend>&nbsp;<?php echo JText::_( "COM_CONFLICTOS_TAB_GENERAL_UBICACION_TITLE" ) ?>&nbsp;</legend>
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'undTerrTema' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </fieldset>
    </div>
</div>

<div class="clr"></div>