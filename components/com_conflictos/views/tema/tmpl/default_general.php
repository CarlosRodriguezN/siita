<?php
    // No direct access to this file
    defined('_JEXEC') or die('Restricted Access');
?>

<div class="width-100 fltlft">
    <fieldset class="adminform">
        <legend>&nbsp;<?php echo JText::_( "COM_CONFLICTOS_TAB_GENERAL_TITLE" ) ?>&nbsp;</legend>
        <div class="width-50 fltlft">
            <ul class="adminformlist">
                <?php  if ( $this->admin ): ?>
                    <?php foreach( $this->form->getFieldset( 'formTema' ) as $field ): ?>
                        <?php $op = substr($field->id,-3, 3);?>
                        <?php  if ( $op == "Txt" ): ?>
                            <li id="li-<?php echo $field->id; ?>" class="hide">
                                <?php echo $field->label; ?>
                                <?php echo $field->input; ?>
                                
                                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                    <span id="saveTipoTema" >
                                        <a href="#" id="saveTT" >
                                            <img src="/media/system/images/mantenimiento/save.png" title="<?php echo JText::_( "BTN_GUARDAR" ) ?>">
                                        </a>
                                    </span>
                                    <span id="cancelTipoTema" >
                                        <a href="#" id="cancelTT" >
                                            <img src="/media/system/images/mantenimiento/close.png" title="<?php echo JText::_( "BTN_CANCELAR" ) ?>">
                                        </a>
                                    </span>
                                <?php endif; ?>
                                
                            </li>
                        <?php elseif ($field->type == "tipostema"): ?>
                            <li id="li-<?php echo $field->id; ?>">
                                <?php echo $field->label; ?>
                                <?php echo $field->input; ?>
                                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                    <span id="gestionTipoTema" >
                                        <span id="addTipoTema" >
                                            <a href="#" id="addTT" >
                                                <img src="/media/system/images/mantenimiento/new.png" title="<?php echo JText::_( "BTN_NUEVO" ) ?>">
                                            </a>
                                        </span>
                                        <span id="updTipoTema" >
                                            <a href="#" id="updTT" >
                                                <img src="/media/system/images/mantenimiento/edit.png" title="<?php echo JText::_( "BTN_EDITAR" ) ?>">
                                            </a>
                                        </span>
                                        <span id="delTipoTema" >
                                            <a href="#" id="delTT">
                                                <img src="/media/system/images/mantenimiento/delete.png" title="<?php echo JText::_( "BTN_ELIMINAR" ) ?>">
                                            </a>
                                        </span>
                                    </span>
                                <?php endif; ?>
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
                        <?php $op = substr($field->id,-3, 3);?>
                        <?php  if ( $op != "Txt" ): ?>
                            <li>
                                <?php echo $field->label; ?>
                                <?php echo $field->input; ?>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
        <div class="width-50 fltrt">
            <input type="file" id="cargaArchivos">
            <table class="tablesorter" id="tbListFilesTema">
                <thead>
                    <tr>
                        <th><?php echo JText::_( "COM_CONFLICTOS_TBL_LSTARCHIVOS_NOMBRE" ) ?> </th>                       </hd>
                        <th colspan="2" align="center"><?php echo JText::_( "TL_ACCIONES" ) ?> </th>                       </hd>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
        <div class="clr"></div>
        <?php echo $this->form->getLabel( 'descripcion' ); ?>
        <div class="clr"></div>
        <?php echo $this->form->getInput( 'descripcion' ); ?>
    </fieldset>
</div>
<div class="clr"> </div>
