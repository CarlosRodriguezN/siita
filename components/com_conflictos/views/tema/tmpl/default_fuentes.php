<?php
    // No direct access to this file
    defined('_JEXEC') or die('Restricted Access');
?>

<div class="width-100 fltlft">
    <div class="width-50 fltrt">
        <div id="imgeFuentes" class="editbackground"></div>    
        <div id="formFuentes" class="hide" >
            <fieldset class="adminform">
                <legend>&nbsp;<?php echo JText::_( "COM_CONFLICTOS_TAB_FUENTES_LSTTITLE" ) ?>&nbsp;</legend>
                <ul class="adminformlist" id="frmTemaFuente">
                    <?php foreach( $this->form->getFieldset( 'fuenteTema' ) as $field ): ?>
                        <li>
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="clr"> </div>
                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                    <input id="saveFuenteTema" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_GUARDAR' ); ?> &nbsp;">
                <?php endif; ?>

                <input id="cancelFuenteTema" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_CANCELAR' ); ?> &nbsp;">
            </fieldset>
        </div>
    </div>
    <div class="width-50 fltleft">
        <fieldset class="adminform">
            <legend>&nbsp;<?php echo JText::_( "COM_CONFLICTOS_TAB_FUENTES_LSTTITLE" ) ?>&nbsp;</legend>
            <div class="fltrt">  
                <input id="addFuenteTema" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_AGREGAR' ); ?> &nbsp;">
            </div> 
            <table id="tbLstFuentes" class="tablesorter" cellspacing="1" >
                <thead>
                    <tr>
                        <th align="center">
                            <?php echo JText::_( 'COM_CONFLICTOS_TABLE_FUENTE_LABEL_MEDIO' ); ?>
                        </th>
                        <th align="center">
                            <?php echo JText::_( 'COM_CONFLICTOS_TABLE_FUENTE_OBSERVACION_MEDIO' ); ?>
                        </th>
                        <th align="center">
                            <?php echo JText::_( 'COM_CONFLICTOS_TABLE_FUENTE_LABEL_TIPO_MEDIO' ); ?>
                        </th>
                        <th align="center">
                            <?php echo JText::_( 'COM_CONFLICTOS_TABLE_FUENTE_LABEL_FECHA' ); ?>
                        </th>
                        <th  colspan="3" align="center" width="15">
                            <?php echo JText::_( 'TL_ACCIONES' ); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if( $this->tema->lstFetTema ): ?>
                        <?php foreach( $this->tema->lstFetTema AS $estado ): ?>
                            <tr id="<?php echo $estado->regFueTema ?>">
                                <td><?php echo $estado->nmbFuente ?></td>
                                <td><?php echo $estado->observacion ?></td>
                                <td><?php echo $estado->nmbTipoFuente ?></td>
                                <td><?php echo $estado->fecha ?></td>
                                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                    <td align="center" width="15" ><a href="#" class="updFuente"><?php echo JText::_( 'LB_EDITAR' ) ?></td>
                                    <td align="center" width="15" ><a href="#" class="delFuente"><?php echo JText::_( 'LB_ELIMINAR' ) ?></td>
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