<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<div class="width-100">
    <div>
        <div id="tabsMarcoLogico" style="position: static; left: 20px; height: auto; width: 100%">
            <ul>
                <li><a href="#mlFin"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_MLFIN_TITLE' ) ?> </a> </li>                            
                <li><a href="#mlProposito"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_MLPROPOSITO_TITLE' ) ?> </a> </li>                            
                <li><a href="#mlComponente" id="tabCmpML"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_MLCOMPONENTE_TITLE' ) ?> </a> </li>
                <li><a href="#mlActividad" id="tabActML"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_MLACTIVIDAD_TITLE' ) ?> </a></li>
            </ul>

            <!-- Marco Logico de tipo FIN -->
            <div id="mlFin">
                <fieldset class="adminform">
                    <legend> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_MLFIN_TITLE' ) ?> </legend>
                    <ul class="adminformlist">
                        <!-- Nombre de tipo Fin -->
                        <?php $mlNombreFin = $this->form->getField( 'txtNombreFin' ); ?>
                        <li>
                            <?php echo $mlNombreFin->label; ?>
                            <?php echo $mlNombreFin->input; ?>
                        </li>

                        <!-- textArea de tipo Fin -->
                        <?php $mlFin = $this->form->getField( 'strMLFin' ); ?>
                        <li>
                            <?php echo $mlFin->label; ?>
                            <?php echo $mlFin->input; ?>
                        </li>

                        <div class="clr"></div>
                        <li>
                            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                <a href="index.php?option=com_proyectos&view=medioverificacion&layout=edit&intCodigo_pry=<?php echo (int) $this->item->intCodigo_pry; ?>&idTipoML=1&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x: 950, y: 500}}"> 
                                    <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_MEDIOVERIFICACION_TITLE' ); ?>
                                </a>
                            <?php endIf; ?>
                        </li>
                    </ul>
                </fieldset>
            </div>

            <!-- Marco Logico de tipo PROPOSITO -->
            <div id="mlProposito">
                <fieldset class="adminform">
                    <legend> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_MLPROPOSITO_TITLE' ) ?> </legend>
                    <ul>
                        <!-- Nombre de tipo Fin -->
                        <?php $mlNombreProposito = $this->form->getField( 'txtNombreProposito' ); ?>
                        <li>
                            <?php echo $mlNombreProposito->label; ?>
                            <?php echo $mlNombreProposito->input; ?>
                        </li>

                        <!-- textArea de tipo proposito -->
                        <?php $mlProposito = $this->form->getField( 'strMLProposito' ); ?>
                        <li>
                            <?php echo $mlProposito->label; ?>
                            <?php echo $mlProposito->input; ?>
                        </li>

                        <div class="clr"></div>

                        <li>
                            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                <a href="index.php?option=com_proyectos&view=medioverificacion&intCodigo_pry=<?php echo (int) $this->item->intCodigo_pry; ?>&idTipoML=2&layout=edit&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x: 950, y: 500}}"> 
                                    <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_MEDIOVERIFICACION_TITLE' ); ?>
                                </a> 
                            <?php endIf; ?>
                        </li>

                    </ul>
                </fieldset>
            </div>

            <!-- Marco Logico de tipo Componente -->
            <div id="mlComponente" class="m">
                <!-- Lista de Componentes -->
                <div class="width-50 fltlft">
                    <fieldset class="adminform">
                        <legend> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_LSTMLCOMPONENTES_TITLE' ) ?> </legend>

                        <div class="fltrt" >
                            <?php if( $this->canDo->get( 'core.create' ) ): ?>
                                <input id="btnAddMLComponente" type="button" value="<?php echo JText::_( 'BTN_AGREGAR_CMP' ) ?>">
                            <?php endIf; ?>
                            <p id="smsAddCmpML" class="hide" ><?php echo JText::_( 'COM_PROYECTOS_SMS_CMP_ML' ) ?></p>
                        </div>
                        
                        <div class="clr"></div>

                        <table id="lstMLComponentes" class="tablesorter" cellspacing="1">
                            <thead>
                                <tr>
                                    <th align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_MLNOMBRECOMPONENTE_LABEL' ) ?> </th>
                                    <th align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_MLCOMPONENTE_LABEL' ) ?> </th>
                                    <th colspan="3" align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_INDICADOR_MLOPERACION_FUENTE' ) ?> </th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if( count( $this->_dtaML["lstComponentes"] ) > 0 ): ?>
                                    <?php $cont = 0; ?>
                                    <?php foreach( $this->_dtaML["lstComponentes"] as $key => $cmp ): ?>
                                        <tr id="<?php echo $key; ?>">
                                            <td> <?php echo $cmp["nombre"]; ?> </td>
                                            <td> <?php echo $cmp["descripcion"]; ?> </td>
                                            <td align='center' width="20px"> <a href="index.php?option=com_proyectos&view=medioverificacion&intCodigo_pry=<?php echo (int) $this->item->intCodigo_pry; ?>&idTipoML=3&idRegML=<?php echo $key; ?>&idML=<?php echo $cmp["idMLCmp"]; ?> &layout=edit&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x: 950, y: 500}}"> Medio de Verificación </a> </td>
                                            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                                <td align='center'> <a class="updMLCmp"> Editar </a> </td>
                                                <td align='center'> <a class="delMLCmp"> Eliminar </a> </td>
                                            <?php else: ?>
                                                <td align='center'> Editar </td>
                                                <td align='center'> Eliminar </td>
                                            <?php endIf; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                            </tbody>
                        </table>
                    </fieldset>
                </div>

                <!-- Formulario de registro de un componente -->
                <div class="width-50 fltrt">
                    <div id="imgCompoente"class="editbackground"></div>
                    <div id="frmCompoente" class=hide>
                        <fieldset class="adminform">
                            <legend> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_MLCOMPONENTE_TITLE' ) ?> </legend>
                            <ul>
                                <li>
                                    <!-- textArea de tipo Fin -->
                                    <label id="jform_strMLFin-lbl" for="jform_strMLFin" class="hasTip" title="" aria-invalid="false"> 
                                        <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_MLFIN_TITLE' ) ?> 
                                        <span class="star">&nbsp;*</span>
                                    </label>
                                    <textarea name="nombreFin" id="nombreFin" cols="5" rows="3" disabled></textarea>
                                </li>

                                <li>
                                    <!-- textArea de tipo Proposito -->
                                    <label id="jform_strMLProposito-lbl" for="jform_strMLProposito" class="hasTip" title="" aria-invalid="false"> 
                                        <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_MLPROPOSITO_TITLE' ) ?> 
                                        <span class="star">&nbsp;*</span>
                                    </label>
                                    <textarea name="nombreProposito" id="nombreProposito" cols="5" rows="3" disabled></textarea>                                
                                </li>
                            </ul>

                            <ul id="frmCmpML">
                                <?php $mlNombreComponente = $this->form->getField( 'txtNombreComponente' ); ?>
                                <li>
                                    <?php echo $mlNombreComponente->label; ?>
                                    <?php echo $mlNombreComponente->input; ?>
                                </li>

                                <?php $mlComponente = $this->form->getField( 'strMLComponente' ); ?>
                                <li>
                                    <?php echo $mlComponente->label; ?>
                                    <?php echo $mlComponente->input; ?>
                                </li>
                            </ul>

                            <div class="clr"></div>
                            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                <input id="btnSaveMLComponente" type="button" value="<?php echo JText::_( "COM_PROYECTOS_FIELD_PROYECTO_SAVECOMPONENTE_TITLE" ) ?>">
                            <?php endIf; ?>

                            <input id="btnLimpiarFrmComponente" type="button" value="Limpiar">
                            <div class="clr"></div>

                        </fieldset>
                    </div>
                </div>
            </div>

            <!-- Marco Logico de tipo ACTIVIDAD -->
            <div id="mlActividad" class="m">

                <!-- Lista de actividades -->
                <div class="width-50 fltlft">
                    <fieldset class="adminform">
                        <legend>&nbsp;<?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_LSTMLACTIVIDAD_TITLE' ) ?>&nbsp;</legend>

                        <div class="fltrt">
                            <input id="btnAddMLActividad" type="button" value="&nbsp;&nbsp;<?php echo JText::_( 'BTN_AGREGAR_ACT' ) ?>&nbsp;&nbsp;">
                            <p id="smsAddActML" class="hide" ><?php echo JText::_( 'COM_PROYECTOS_SMS_ACT_ML' ) ?></p>
                        </div>
                        <div class="clr"></div>

                        <table id="lstMLActividades" width="100%" class="tablesorter" cellspacing="1">
                            <thead>
                                <tr>
                                    <th align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_MLNOMBREACTIVIDAD_LABEL' ) ?> </th>
                                    <th align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_MLNOMBRECOMPONENTE_LABEL' ) ?> </th>
                                    <th align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_MLACTIVIDAD_LABEL' ) ?> </th>                                
                                    <th colspan="3" align="center"> <?php echo JText::_( 'COM_PROYECTOS_FIELD_INDICADOR_MLOPERACION_FUENTE' ) ?> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if( count( $this->_dtaML["lstComponentes"] ) > 0 ): ?>
                                    <?php foreach( $this->_dtaML["lstComponentes"] as $key => $cmp ): ?>
                                        <?php if( count( $cmp["lstActividad"] ) > 0 ): ?>
                                            <?php foreach( $cmp["lstActividad"] as $keyAct => $act ): ?>
                                                <tr id='<?php echo $key . '|' . $keyAct; ?>'>
                                                    <td align='center'> <?php echo $cmp["nombre"]; ?> </td>
                                                    <td align='center'> <?php echo $act["nombre"]; ?> </td>
                                                    <td align='center'> <?php echo $act["descripcion"]; ?> </td>
                                                    <td align='center' width="20px"> <a href="index.php?option=com_proyectos&view=medioverificacion&intCodigo_pry=<?php echo (int) $this->item->intCodigo_pry; ?>&idTipoML=4&idRegCmp=<?php echo $key; ?>&idRegAct=<?php echo $keyAct; ?> &layout=edit&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x: 950, y: 500}}"> Medio de Verificación </a> </td>
                                                    
                                                    <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                                        <td align='center' > <a class="updMLAct"> Editar </a> </td>
                                                        <td align='center' > <a class="delMLAct"> Eliminar </a> </td>
                                                    <?php else: ?>
                                                        <td align='center' > Editar </td>
                                                        <td align='center' > Eliminar </td>
                                                    <?php endIf;?>
                                                        
                                                </tr>

                                            <?php endforeach; ?>

                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </fieldset>
                </div>

                <!-- Formulario de registro de una actividad -->
                <div class="width-50 fltrt">
                    <div id="imgActividad" class="editbackground"></div>
                    <div id="frmActividad" class="hide">
                        <fieldset class="adminform">
                            <legend>&nbsp;<?php echo JText::_( 'COM_PROYECTOS_FIELD_PROYECTO_MLACTIVIDAD_TITLE' ) ?>&nbsp;</legend>
                            <ul class="adminformlist" id="frmActML">

                                <!-- ComboBox de tipo Componente -->
                                <?php $cbComponente = $this->form->getField( 'cbMLComponente' ); ?>
                                <li>
                                    <?php echo $cbComponente->label; ?>
                                    <?php echo $cbComponente->input; ?>
                                </li>

                                <?php $mlNombreActividad = $this->form->getField( 'txtNombreActividad' ); ?>
                                <li>
                                    <?php echo $mlNombreActividad->label; ?>
                                    <?php echo $mlNombreActividad->input; ?>
                                </li>

                                <?php $mlActividad = $this->form->getField( 'strMLActividad' ); ?>
                                <li>
                                    <?php echo $mlActividad->label; ?>
                                    <?php echo $mlActividad->input; ?>
                                </li>
                            </ul>

                            <div class="clr"></div>
                            <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                <input id="btnSaveMLActividad" type="button" value="<?php echo JText::_( 'BTN_GUARDAR' ) ?>">
                            <?php endIf; ?>
                                
                            <input id="btnLimpiarFrmActividad" type="button" value="Limpiar">

                            <?php $dtaMarcoLogico = $this->form->getField( 'dtaMarcoLogico' ); ?>
                            <?php echo $dtaMarcoLogico->input; ?>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>