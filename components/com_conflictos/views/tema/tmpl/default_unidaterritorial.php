<?php
    // No direct access to this file
    defined('_JEXEC') or die('Restricted Access');
?>

<div class="width-100 fltlft">
    <div class="width-50 fltrt">
        <div id="imgeUnidadTerritorial" class="editbackground"></div>    
        <div id="formUnidadTerritorial" class="hide" >
            <fieldset class="adminform">
                <legend>&nbsp;<?php echo JText::_( "COM_CONFLICTOS_TAB_UNDTERR_TITLE" ) ?>&nbsp;</legend>
                <ul class="adminformlist" id="formTemaUndTrr">
                    <?php foreach( $this->form->getFieldset( 'undTerrTema' ) as $field ): ?>
                        <li>
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="clr">
                </div>
                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                    <input id="saveUnidadTerritorialTema" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_GUARDAR' ); ?> &nbsp;">
                <?php endif; ?>

                <input id="cancelUnidadTerritorialTema" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_CANCELAR' ); ?> &nbsp;">
            </fieldset>
        </div>
    </div>
    <div class="width-50 fltleft">
        <fieldset class="adminform">
            <legend>&nbsp;<?php echo JText::_( "COM_CONFLICTOS_TAB_UNDTERR_LSTTITLE" ) ?>&nbsp;</legend>
            <?php if( $this->canDo->get( 'core.create' ) ): ?>
                <div class="fltrt">  
                    <input id="addUnidadTerritorialTema" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_AGREGAR' ); ?> &nbsp;">
                </div> 
            <?php endif; ?>

            <table id="tbLstUnidadTerritorial" class="tablesorter" cellspacing="1" >
                <thead>
                    <tr>
                        <th align="center">
                            <?php echo JText::_( 'COM_CONFLICTOS_TABLE_UNDTERR_PROVINCIA' ); ?>
                        </th>
                        <th align="center">
                            <?php echo JText::_( 'COM_CONFLICTOS_TABLE_UNDTERR_CANTON' ); ?>
                        </th>
                        <th align="center">
                            <?php echo JText::_( 'COM_CONFLICTOS_TABLE_UNDTERR_PARROQUIA' ); ?>
                        </th>
                        <th  colspan="3" align="center" width="15">
                            <?php echo JText::_( 'TL_ACCIONES' ); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if( $this->tema->lstUnidadesTerritoriales ): ?>
                        <?php foreach( $this->tema->lstUnidadesTerritoriales AS $estado ): ?>
                            <tr id="<?php echo $estado->regUnidadTerritorial ?>">
                                <td><?php echo $estado->provincia ?></td>
                                <td><?php echo $estado->canton ?></td>
                                <td><?php echo $estado->parroquia ?></td>
                                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                    <td align="center" width="15" ><a class="updUnidadTerritorial"><?php echo JText::_( 'LB_EDITAR' ) ?></td>
                                    <td align="center" width="15" ><a class="delUnidadTerritorial"><?php echo JText::_( 'LB_ELIMINAR' ) ?></td>
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