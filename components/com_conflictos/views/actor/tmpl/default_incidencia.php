<?php
    // No direct access to this file
    defined('_JEXEC') or die('Restricted Access');
?>

<div class="width-100 fltlft">
    <div class="width-50 fltrt">
            <div id="imgeIncidenaciaActor" class="editbackground"></div>    
        <div id="formIncidenaciaActor" class="hide" >
            <fieldset class="adminform">
                <legend>&nbsp;<?php echo JText::_( "COM_CONFLICTOS_TAB_INCIDENCIA_ACTOR_TITLE" ) ?>&nbsp;</legend>
                <ul class="adminformlist" id="formIncidenciaAct">
                    <?php  if ( $this->admin ): ?>
                        <?php foreach( $this->form->getFieldset( 'incidenciaActor' ) as $field ): ?>
                            <?php $op = substr($field->id,-3, 3);?>
                            <?php  if ( $op == "Txt" ): ?>
                                <li id="li-<?php echo $field->id; ?>" class="hide">
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                    
                                    <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                        <span id="saveIncidencia" >
                                            <a href="#" id="saveInc" >
                                                <img src="/media/system/images/mantenimiento/save.png" title="<?php echo JText::_( "BTN_GUARDAR" ) ?>">
                                            </a>
                                        </span>
                                        <span id="cancelIncidencia" >
                                            <a href="#" id="cancelInc" >
                                                <img src="/media/system/images/mantenimiento/close.png" title="<?php echo JText::_( "BTN_CANCELAR" ) ?>">
                                            </a>
                                        </span>
                                    <?php endif; ?>
                                </li>
                            <?php elseif ($field->type == "incidencias"): ?>
                                <li id="li-<?php echo $field->id; ?>">
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                    <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>                    

                                        <span id="gestionIncidencia" >
                                            <span id="addIncidencia" >
                                                <a href="#" id="addInc" >
                                                    <img src="/media/system/images/mantenimiento/new.png" title="<?php echo JText::_( "BTN_NUEVO" ) ?>">
                                                </a>
                                            </span>
                                            <span id="updIncidencia" >
                                                <a href="#" id="updInc" >
                                                    <img src="/media/system/images/mantenimiento/edit.png" title="<?php echo JText::_( "BTN_EDITAR" ) ?>">
                                                </a>
                                            </span>
                                            <span id="delIncidencia" >
                                                <a href="#" id="delInc" >
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
                        <?php foreach( $this->form->getFieldset( 'incidenciaActor' ) as $field ): ?>
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
                    <input id="saveIncidenciaActor" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_GUARDAR' ); ?> &nbsp;">
                <?php endif; ?>

                <input id="cancelIncidenciaActor" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_CANCELAR' ); ?> &nbsp;">
            </fieldset>
        </div>
    </div>
    <div class="width-50 fltleft">
        <fieldset class="adminform">
            <legend>&nbsp;<?php echo JText::_( "COM_CONFLICTOS_TAB_INCIDENCIA_ACTOR_LSTTITLE" ) ?>&nbsp;</legend>
            <div class="fltrt">  
                <input id="addIncidenciaActor" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_AGREGAR' ); ?> &nbsp;">
            </div> 
            <table id="tbLstIncidencasFuete" class="tablesorter" cellspacing="1" >
                <thead>
                    <tr>
                        <th align="center">
                            <?php echo JText::_( 'COM_CONFLICTOS_TABLE_INCIDENCIA_INCIDENCIA' ); ?>
                        </th>
                        <th align="center">
                            <?php echo JText::_( 'COM_CONFLICTOS_TABLE_INCIDENCIA_FECHA' ); ?>
                        </th>
                        <th  colspan="3" align="center" width="15">
                            <?php echo JText::_( 'TL_ACCIONES' ); ?>
                        </th>
                    </tr>
                </thead>
                
                <tbody>
                </tbody>
            </table>
        </fieldset>
    </div>
</div>
<div class="clr"> </div>