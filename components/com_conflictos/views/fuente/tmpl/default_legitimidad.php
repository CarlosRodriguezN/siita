<?php
    // No direct access to this file
    defined('_JEXEC') or die('Restricted Access');
?>

<div class="width-100 fltlft">
    <div class="width-50 fltrt">
        <div id="imgeLegitimidadFuente" class="editbackground"></div>    
        <div id="formLegitimidadFuente" class="hide" >
            <fieldset class="adminform">
                <legend>&nbsp;<?php echo JText::_( "COM_CONFLICTOS_TAB_LEGITIMIDAD_TITLE" ) ?>&nbsp;</legend>
                <ul class="adminformlist" id="formLegitimidadFnt">
                    <?php  if ( $this->admin ): ?>
                        <?php foreach( $this->form->getFieldset( 'legitimdadFuente' ) as $field ): ?>
                            <?php $op = substr($field->id,-3, 3);?>
                            <?php  if ( $op == "Txt" ): ?>
                                <li id="li-<?php echo $field->id; ?>" class="hide">
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                    <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                        <span id="saveLegitimidad" >
                                            <a href="#" id="saveLeg" >
                                                <img src="/media/system/images/mantenimiento/save.png" title="<?php echo JText::_( "BTN_GUARDAR" ) ?>">
                                            </a>
                                        </span>
                                        <span id="cancelIncidencia" >
                                            <a href="#" id="cancelLeg" >
                                                <img src="/media/system/images/mantenimiento/close.png" title="<?php echo JText::_( "BTN_CANCELAR" ) ?>">
                                            </a>
                                        </span>
                                    <?php endif;?>
                                </li>
                            <?php elseif ($field->type == "legitimidades"): ?>
                                <li id="li-<?php echo $field->id; ?>">
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                    <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                        <span id="gestionLegitimidad" >
                                            <span id="addLegitimidad" >
                                                <a href="#" id="addLeg" >
                                                    <img src="/media/system/images/mantenimiento/new.png" title="<?php echo JText::_( "BTN_NUEVO" ) ?>">
                                                </a>
                                            </span>
                                            <span id="updLegitimidad" >
                                                <a href="#" id="updLeg" >
                                                    <img src="/media/system/images/mantenimiento/edit.png" title="<?php echo JText::_( "BTN_EDITAR" ) ?>">
                                                </a>
                                            </span>
                                            <span id="delLegitimidad" >
                                                <a href="#" id="delLeg" >
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
                        <?php foreach( $this->form->getFieldset( 'legitimdadFuente' ) as $field ): ?>
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
                <div class="clr"></div>
                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                    <input id="saveLegitimidadFuente" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_GUARDAR' ); ?> &nbsp;">
                <?php endif;?>

                <input id="cancelLegitimidadFuente" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_CANCELAR' ); ?> &nbsp;">
            </fieldset>
        </div>
    </div>
    <div class="width-50 fltleft">
        <fieldset class="adminform">
            <legend>&nbsp;<?php echo JText::_( "COM_CONFLICTOS_TAB_LEGITIMIDAD_LSTTITLE" ) ?>&nbsp;</legend>
            <?php if( $this->canDo->get( 'core.create' ) ): ?>
                <div class="fltrt">  
                    <input id="addLegitimidadFuente" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_AGREGAR' ); ?> &nbsp;">
                </div> 
            <?php endif;?>

            <table id="tbLstlegitimidadesFuente" class="tablesorter" cellspacing="1" >
                <thead>
                    <tr>
                        <th align="center">
                            <?php echo JText::_( 'COM_CONFLICTOS_TABLE_LEGITIMIDAD_LEGITIMIDAD' ); ?>
                        </th>
                        <th align="center">
                            <?php echo JText::_( 'COM_CONFLICTOS_TABLE_LEGITIMIDAD_DESCRIPCION' ); ?>
                        </th>
                        <th align="center">
                            <?php echo JText::_( 'COM_CONFLICTOS_TABLE_LEGITIMIDAD_FECHA' ); ?>
                        </th>
                        <th  colspan="3" align="center" width="15">
                            <?php echo JText::_( 'TL_ACCIONES' ); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if( $this->fuente->lstLegitimidad ): ?>
                        <?php foreach( $this->fuente->lstLegitimidad AS $legitimidad ): ?>
                            <tr id="<?php echo $legitimidad->regLegitimidad ?>">
                                <td><?php echo $legitimidad->nmbLegitimidad ?></td>
                                <td><?php echo $legitimidad->descripcion ?></td>
                                <td><?php echo $legitimidad->fecha ?></td>
                                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                    <td align="center" width="15" ><a href="#" class="updLegitimidadFuente"><?php echo JText::_( 'LB_EDITAR' ) ?></td>
                                    <td align="center" width="15" ><a href="#" class="delLegitimidadFuente"><?php echo JText::_( 'LB_ELIMINAR' ) ?></td>
                                <?php else: ?>                                    
                                    <td align="center" width="15" > <?php echo JText::_( 'LB_EDITAR' ) ?> </td>
                                    <td align="center" width="15" > <?php echo JText::_( 'LB_ELIMINAR' ) ?> </td>
                                <?php endif;?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </fieldset>
    </div>
</div>

<div class="clr"></div>