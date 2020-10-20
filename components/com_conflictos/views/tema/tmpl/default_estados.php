<?php
    // No direct access to this file
    defined('_JEXEC') or die('Restricted Access');
?>

<div class="width-100 fltlft">
    <div class="width-50 fltrt">
        <div id="imgeEstados" class="editbackground"></div>    
        <div id="formEstados" class="hide" >
            <fieldset class="adminform">
                <legend>&nbsp;<?php echo JText::_( "COM_CONFLICTOS_TAB_ESTADOS_TITLE" ) ?>&nbsp;</legend>
                <ul class="adminformlist" id="formTemaEstado">
                    <?php  if ( $this->admin ): ?>
                        <?php foreach( $this->form->getFieldset( 'estadoTema' ) as $field ): ?>
                            <?php $op = substr($field->id,-3, 3);?>
                            <?php  if ( $op == "Txt" ): ?>
                                <li id="li-<?php echo $field->id; ?>" class="hide">
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                    
                                    <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                        <span id="saveEstado" >
                                            <a href="#" id="saveEst" >
                                                <img src="/media/system/images/mantenimiento/save.png" title="<?php echo JText::_( "BTN_GUARDAR" ) ?>">
                                            </a>
                                        </span>
                                        <span id="cancelEstado" >
                                            <a href="#" id="cancelEst" >
                                                <img src="/media/system/images/mantenimiento/close.png" title="<?php echo JText::_( "BTN_CANCELAR" ) ?>">
                                            </a>
                                        </span>
                                    <?php endIf;?>

                                </li>
                            <?php elseif ($field->type == "estados"): ?>
                                <li id="li-<?php echo $field->id; ?>">
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                    
                                        <span id="gestionEstado" >
                                            <?php if( $this->canDo->get( 'core.create' ) ): ?>
                                                <span id="addEstado" >
                                                    <a href="#" id="addEst" >
                                                        <img src="/media/system/images/mantenimiento/new.png" title="<?php echo JText::_( "BTN_NUEVO" ) ?>">
                                                    </a>
                                                </span>
                                            <?php endIf; ?>
                                            
                                            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                                <span id="updEstado" >
                                                    <a href="#" id="updEst" >
                                                        <img src="/media/system/images/mantenimiento/edit.png" title="<?php echo JText::_( "BTN_EDITAR" ) ?>">
                                                    </a>
                                                </span>
                                                <span id="delEstado" >
                                                    <a href="#" id="delEst">
                                                        <img src="/media/system/images/mantenimiento/delete.png" title="<?php echo JText::_( "BTN_ELIMINAR" ) ?>">
                                                    </a>
                                                </span>
                                            <?php endIf; ?>
                                        </span>
                                </li>
                            <?php else: ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                           <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php foreach( $this->form->getFieldset( 'estadoTema' ) as $field ): ?>
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
                    <input id="saveEstadoTema" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_GUARDAR' ); ?> &nbsp;">
                <?php endif; ?>

                <input id="cancelEstadosTema" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_CANCELAR' ); ?> &nbsp;">
            </fieldset>
        </div>
    </div>
    <div class="width-50 fltleft">
        <fieldset class="adminform">
            <legend>&nbsp;<?php echo JText::_( "COM_CONFLICTOS_LST_ESTADOS_TITLE" ) ?>&nbsp;</legend>
            <div class="fltrt">  
                <input id="addEstadosTema" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_AGREGAR' ); ?> &nbsp;">
            </div> 
            <table id="tbLstEstados" class="tablesorter" cellspacing="1" >
                <thead>
                    <tr>
                        <th align="center">
                            <?php echo JText::_( 'COM_CONFLICTOS_TABLE_ESTADOS_LABEL_ESTADO' ); ?>
                        </th>
                        <th align="center">
                            <?php echo JText::_( 'COM_CONFLICTOS_TABLE_ESTADOS_LABEL_FECHA_INICIO' ); ?>
                        </th>
                        <th align="center">
                            <?php echo JText::_( 'COM_CONFLICTOS_TABLE_ESTADOS_LABEL_FECHA_FIN' ); ?>
                        </th>
                        <th  colspan="3" align="center" width="15">
                            <?php echo JText::_( 'TL_ACCIONES' ); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if( $this->tema->lstEstTema ): ?>
                        <?php foreach( $this->tema->lstEstTema AS $estado ): ?>
                            <tr id="<?php echo $estado->regEstTema ?>">
                                <td><?php echo $estado->nmbEstado ?></td>
                                <td><?php echo $estado->fechaInicio ?></td>
                                <td><?php echo $estado->fechaFin ?></td>
                                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                    <td align="center" width="15" ><a href="#" class="updEstTema"><?php echo JText::_( 'LB_EDITAR' ) ?></td>
                                    <td align="center" width="15" ><a href="#" class="delEstTema"><?php echo JText::_( 'LB_ELIMINAR' ) ?></td>

                                <?php else: ?>    
                                    <td align="center" width="15" > <?php echo JText::_( 'LB_EDITAR' ) ?> </td>
                                    <td align="center" width="15" > <?php echo JText::_( 'LB_ELIMINAR' ) ?> </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </fieldset>
    </div>
</div>

<div class="clr"> </div>