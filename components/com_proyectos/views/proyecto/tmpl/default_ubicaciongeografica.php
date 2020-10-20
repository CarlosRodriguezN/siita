<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

?>

<div class="width-100">
    <div id="tabsUbicacionGeografica" style="position: static; left: 20px; height: auto; width: 100%">
        <ul>
            <li><a href="#unidadTerritorial"> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_UBCGEOGRAFICAS_TITLE') ?> </a> </li>
            <li><a href="#coordenadas" id="vwCoodenada"> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_COORDENADAS_TITLE') ?> </a></li>
        </ul>

        <!-- Ubicacion Territorial -->
        <div id="unidadTerritorial" class="m">
            <!-- Unidades Territoriales Registradas por el usuario -->
            <div class="width-50 fltlft">
                <div class="width-100">
                    <fieldset class="adminform">
                        <legend> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_LSTUNDTERRITORIAL_TITLE') ?> </legend>
                        <div class="fltrt">
                            <?php if( $this->canDo->get( 'core.create' ) ): ?>
                                <input id="addUbicTerriTablePry" type="button" value="<?php echo JText::_('BTN_AGREGAR_UT_PRY') ?>">
                            <?php endIf; ?>
                        </div>

                        <table id="lstUndTerritoriales" width="100%" class="tablesorter" cellspacing="1">
                            <thead>
                                <tr>
                                    <th align="center"> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_PROVINCIA_LABEL') ?> </th>
                                    <th align="center"> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_CANTON_LABEL') ?> </th>
                                    <th align="center"> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_PARROQUIA_LABEL') ?> </th>                                
                                    <th colspan="2" align="center"> <?php echo JText::_('COM_PROYECTOS_FIELD_INDICADOR_OPERACION_FUENTE') ?> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($this->undTerritorial): ?>
                                    <?php foreach ($this->undTerritorial as $key => $utRow): ?>
                                        <tr id="<?php echo $key ?>">
                                            <td align="center"><?php echo $utRow->provincia ?></td>
                                            <td align="center"><?php 
                                            if ($utRow->idCanton) {
                                                echo $utRow->canton;
                                            } else {
                                                echo '---';
                                            } 
                                            ?></td>
                                            <td align="center"><?php 
                                            if ($utRow->idParroquia) {
                                                echo $utRow->parroquia;
                                            } else {
                                                echo '---';
                                            }
                                            ?></td>
                                            
                                            <?php if( $this->canDo->get( 'core.create' ) ): ?>
                                                <td align="center" width="15" > <a id="updUTPry-<?php echo $key ?>" class="updUndTerritorial">Editar</a></td>
                                                <td align="center" width="15" > <a id="delUTPry-<?php echo $key ?>" class="delUndTerritorial">Eliminar</a></td>
                                            <?php else: ?>
                                                <td align="center" width="15" > Editar </td>
                                                <td align="center" width="15" > Eliminar </td>
                                            <?php endIf; ?>
                                            
                                        </tr>
                                    <?php endforeach; ?> 
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </fieldset>
                </div>
            </div>
            <!-- Formulario de ubicaciones territoriales -->
            <div class="width-50 fltrt">
                <div id="imgUbiTerritorialPry" style=" 
                     background: url(images/logo_default.jpg);
                     background-size: contain;
                     background-repeat: no-repeat;
                     background-position: center center;
                     width: 100%;
                     height: 213px;"
                     >
                </div>
                <div id="frmUbiTerritorialPry" class="hide">
                    <fieldset class="adminform">
                        <legend> Datos Proyecto </legend>
                        <ul class="adminformlist">
                            <?php foreach ($this->form->getFieldset('ubicacionGeografica') as $field): ?>
                                <li>
                                    <?php echo $field->label; ?>
                                    <?php echo $field->input; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="clr"></div>
                        <?php if( $this->canDo->get( 'core.create' ) ): ?>
                            <input id="btnAddUndTerritorial" type="button" value="<?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_ADDUNDTERRITORIAL_TITLE') ?>">
                            <input id="btnSaveChangesUndTerritorial" style="display: none" type="button" value="<?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_SAVECHANGESUNDTERRITORIAL_TITLE') ?>">
                        <?php endIf; ?>

                        <input id="btnLimpiarUndTerritorial" type="button" value="<?php echo JText::_('BTN_CANCELAR') ?>">
                        <div class="clr"></div>
                    </fieldset>
                </div>
            </div>
        </div>

        <!-- Coordenadas Geograficas de la Obra -->
        <div id="coordenadas" class="m">
            <!-- Lista de Graficos Registrados por el usuario -->
            <div class="width-50 fltlft" >
                <div class="width-100 fltlft">
                    <fieldset class="adminform">
                        <legend> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_LSTGRAFICOS_TITLE') ?> </legend>
                        <div class="fltrt">
                            <?php if( $this->canDo->get( 'core.create' ) ): ?>
                                <input id="addGraficosObraTable" type="button" value="<?php echo JText::_('BTN_AGREGAR_UG_PRY') ?>">
                            <?php endIf; ?>
                        </div>

                        <table id="tbLstGraficos" width="100%" class="tablesorter" cellspacing="1">
                            <thead>
                                <tr>
                                    <th align="center"> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_DESCRIPCION_GRAFICO_LABEL') ?> </th>
                                    <th align="center"> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_TPOGRAFICO_LABEL') ?> </th>
                                    <th colspan="3" align="center"> <?php echo JText::_('COM_PROYECTOS_FIELD_INDICADOR_OPERACION_FUENTE') ?> </th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if ($this->lstGraficos): ?>
                                    <?php foreach ($this->lstGraficos as $key => $grafico): ?>
                                        <tr id="<?php
                                        $idReg = $key + 1;
                                        echo $idReg;
                                        ?>">
                                            <td align="center" ><?php echo $grafico->descGrafico; ?></td>
                                            <td align="center" ><?php echo $grafico->infoTpoGrafico; ?></td>
                                            <td align="center" width="15" > <a id='showGraf-<?php echo $idReg ?>' class="showGrafico" > Ver </a> </td>
                                            <?php if( $this->canDo->get( 'core.create' ) ): ?>
                                                <td align="center" width="15" > <a id='updGraf-<?php echo $idReg ?>' class="updGrafico" > Editar </a> </td>
                                                <td align="center" width="15" > <a id='delGraf-<?php echo $idReg ?>' class="delGrafico" > Eliminar </a> </td>   
                                            <?php else:?>    
                                                <td align="center" width="15" > Editar </td>
                                                <td align="center" width="15" > Eliminar </td>
                                            <?php endIf; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </fieldset>
                </div>
                <div class="width-100 fltlft" >
                    <fieldset class="adminform">
                        <legend> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_MAPA_TITLE') ?> </legend>
                        <div id="mapa_proyectosdiv" style="width:100%; height:400px ">
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="width-50 fltrt">
                <div id="imgUbicacionGeografica" style=" 
                     background: url(images/logo_default.jpg);
                     background-size: contain;
                     background-repeat: no-repeat;
                     background-position: center center;
                     width: 100%;
                     height: 213px;"
                     >
                </div>
                <div id="frmUbicacionGeografica" class ="hide width-100">
                    <fieldset class="adminform">
                        <legend> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_TPOGRAFICO_TITLE') ?> </legend>
                        <div id="frmTipoGraficoUG" class="width-100" >
                            <ul class="adminformlist" id="formGraficoObraPry">
                                <?php foreach ($this->form->getFieldset('coordenadasGeograficas') as $field): ?>
                                    <li>
                                        <?php echo $field->label; ?>
                                        <?php echo $field->input; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div id="sumitDtaGeneralUG" class ="width-100">
                                <?php if( $this->canDo->get( 'core.create' ) ): ?>
                                    <input id="btnAddLstCoordenadas" type="button" value="<?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_ADDCOORDENADAS_TITLE') ?>">
                                <?php endIf; ?>

                                <input id="btnCancelarLstCoor" type="button" value="<?php echo JText::_('BTN_CANCELAR') ?>">
                            </div>
                        </div>
                        <div class="clr"></div>
                        <!-- Registro de vertices de un determinado Grafico -->
                        <div id="frmCoordenadasUG"  class="hide width-100" >
                            <fieldset class="adminform">
                                <legend> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_PTOSGRAFICO_TITLE') ?> </legend>
                                <!--Coordenadas LAT LONG-->
                                <ul id="formGraficoCoordenada" >
                                    <?php foreach ($this->form->getFieldset('coordenadasObra') as $field): ?>
                                        <li>
                                            <?php echo $field->label; ?>
                                            <?php echo $field->input; ?>
                                        </li>
                                    <?php endforeach; ?>

                                <!--Solo para el circulo-->
                                    <?php $fieldCircle = $this->form->getField('Radio'); ?>
                                    <li id="obras-radio" style="display:none">
                                        <?php echo $fieldCircle->label; ?>
                                        <?php echo $fieldCircle->input; ?>
                                    </li>
                                </ul>

                                <div class="clr"></div>
                                <div>
                                    <?php if( $this->canDo->get( 'core.create' ) ): ?>
                                        <input id="btnAddCoordenada" type="button" value="<?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_ADDCOORDENADA_TITLE') ?>">
                                    <?php endIf; ?>

                                    <input id="btnLimpiarGrafico" type="button" value="<?php echo JText::_('BTN_LIMPIAR') ?>">
                                </div>
                                <div class="clr"></div>

                                <ul>
                                    <li id="puntos-table">
                                        <table id="tbLstPuntos" width="100%" class="tablesorter" cellspacing="1">
                                            <thead>
                                                <tr>
                                                    <th align="center"> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_LATITUD_LABEL') ?> </th>
                                                    <th align="center"> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_LONGITUD_LABEL') ?> </th>
                                                    <th colspan="3" align="center"> <?php echo JText::_('COM_PROYECTOS_FIELD_INDICADOR_OPERACION_FUENTE') ?> </th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </li>
                                    <li id="radio-table" style="display: none">
                                        <table id="tbLstCirculo" width="100%" class="tablesorter" cellspacing="1">
                                            <thead>
                                                <tr>
                                                    <th align="center"> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_LATITUD_LABEL') ?> </th>
                                                    <th align="center"> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_LONGITUD_LABEL') ?> </th>
                                                    <th align="center"> <?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_RADIO_LABEL') ?> </th>
                                                    <th colspan="3" align="center"> <?php echo JText::_('COM_PROYECTOS_FIELD_INDICADOR_OPERACION_FUENTE') ?> </th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </li>
                                </ul>
                            </fieldset>
                            <div class="clr"></div>
                            <div>
                                <?php if( $this->canDo->get( 'core.create' ) ): ?>
                                    <input id="btnAddGrafico" type="button" value="<?php echo JText::_('COM_PROYECTOS_FIELD_PROYECTO_ADDGRAFICO_TITLE') ?>">
                                <?php endIf; ?>

                                <input id="btnLimpiarPuntos" type="button" value="<?php echo JText::_('BTN_CANCELAR') ?>">
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>

