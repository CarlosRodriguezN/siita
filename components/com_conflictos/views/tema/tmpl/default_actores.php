<?php
    // No direct access to this file
    defined('_JEXEC') or die('Restricted Access');
?>

<div class="width-100 fltlft">
    <div class="width-50 fltrt">
        <div id="imgeActDeta" class="editbackground"></div>    
        <div id="formActDeta" class="hide" >
            <fieldset class="adminform">
                <legend>&nbsp;<?php echo JText::_( "COM_CONFLICTOS_TAB_ACTORES_TITLE" ) ?>&nbsp;</legend>
                <ul class="adminformlist" id="formTemaActor">
                    <?php foreach( $this->form->getFieldset( 'actorTema' ) as $field ): ?>
                        <li>
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="clr"> </div>
                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                    <input id="saveActorTema" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_GUARDAR' ); ?> &nbsp;">
                <?php endif; ?>
                <input id="cancelActorTema" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_CANCELAR' ); ?> &nbsp;">
            </fieldset>
        </div>
        <div id="documActDeta" class="hide" >
            <fieldset class="adminform">
                <legend>&nbsp;<?php echo JText::_( "COM_CONFLICTOS_TAB_ACTOR_DOCUMENTOS_TITLE" ) ?>&nbsp;</legend>
                <input type="file" id="cargaArchivosActor">
                <table id="tbDocsActorDetalle" class="tablesorter">
                    <thead>
                        <tr>
                            <th><?php echo JText::_( "COM_CONFLICTOS_TBL_LSTARCHIVOS_ACTOR_TITLE" ) ?> </th>                       </hd>
                            <th colspan="2"><?php echo JText::_( "TL_ACCIONES" ) ?> </th>                       </hd>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div class="clr"> </div>
                <input id="cerrarDocActorTema" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_CERRAR' ); ?> &nbsp;">
            </fieldset>
        </div>
    </div>
    <div class="width-50 fltleft">
        <fieldset class="adminform">
            <legend>&nbsp;<?php echo JText::_( "COM_CONFLICTOS_LST_ACTDETA_TITLE" ) ?>&nbsp;</legend>
            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                <div class="fltrt">  
                    <input id="addActorTema" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_AGREGAR' ); ?> &nbsp;">
                </div> 
            <?php endif; ?>

            <table id="tbLstActores" class="tablesorter" cellspacing="1" >
                <thead>
                    <tr>
                        <th align="center">
                            <?php echo JText::_( 'COM_CONFLICTOS_TABLE_ACTORES_LABEL_ACTOR' ); ?>
                        </th>
                        <th align="center">
                            <?php echo JText::_( 'COM_CONFLICTOS_TABLE_ACTORES_LABEL_DISCURSO' ); ?>
                        </th>
                        <th align="center">
                            <?php echo JText::_( 'COM_CONFLICTOS_TABLE_ACTORES_LABEL_FECHA' ); ?>
                        </th>
                        <th  colspan="3" align="center" width="15">
                            <?php echo JText::_( 'TL_ACCIONES' ); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if( $this->tema->lstActDeta ): ?>
                        <?php foreach( $this->tema->lstActDeta AS $actor ): ?>
                            <tr id="<?php echo $actor->regActDeta ?>">
                                <td><?php echo $actor->nmbActor ?></td>
                                <td><?php echo $actor->descripcion ?></td>
                                <td><?php echo $actor->fecha ?></td>

                                <td align="center" width="15" ><a href="#" class="docActDeta"><?php echo JText::_( 'LB_DOCUMENTOS' ) ?></td>
                                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                    <td align="center" width="15" ><a href="#" class="updActDeta"><?php echo JText::_( 'LB_EDITAR' ) ?></td>
                                    <td align="center" width="15" ><a href="#" class="delActDeta"><?php echo JText::_( 'LB_ELIMINAR' ) ?></td>
                                <?php else: ?>    
                                    <td align="center" width="15" > <?php echo JText::_( 'LB_EDITAR' ) ?> </td>
                                    <td align="center" width="15" > <?php echo JText::_( 'LB_ELIMINAR' ) ?> </td>
                                <?php endIf; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </fieldset>
    </div>
</div>

<div class="clr"> </div>