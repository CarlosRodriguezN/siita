<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<!-- Lista de Graficos Registrados por el usuario -->
<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_LSTGRAFICOS_TITLE') ?> </legend>
        <?php if( $this->canDo->get( 'core.create' ) ): ?>
            <div class="fltrt">
                <input id="addGraficosObraTable" type="button" value="<?php echo JText::_('BTN_AGREGAR') ?>">
            </div>
        <?php endIf; ?>

            <table id="tbLstGraficos" class="tablesorter" cellspacing="1">
                <thead>
                    <tr>
                        <th align="center"> <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_DESCRIPCION_GCP_LABEL') ?> </th>
                        <th align="center"> <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_TIPO_GRAFICO_LABEL') ?> </th>
                        <th colspan="3" align="center"> <?php echo JText::_('COM_CANASTAPROY_FIELD_INDICADOR_OPERACION_FUENTE') ?> </th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($this->ubicGeografica): ?>
                        <?php foreach ($this->ubicGeografica as $key => $grafico): ?>
                            <tr id="<?php
                            $idReg = $key + 1;
                            echo $idReg;
                            ?>">
                                <td align="center" ><?php echo $grafico->strDescripcionGrafico_gcp; ?></td>
                                <td align="center" ><?php echo $grafico->strDescripcion_tg; ?></td>
                                <td align="center" width="15" > <a id='showGraf-<?php echo $idReg ?>' class="showGrafico" > Ver </a> </td>
                                <td align="center" width="15" > <a id='updGraf-<?php echo $idReg ?>' class="updGrafico" > Editar </a> </td>
                                <td align="center" width="15" > <a id='delGraf-<?php echo $idReg ?>' class="delGrafico" > Eliminar </a> </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                </tbody>
            </table>
    </fieldset>
    <div class="width-100" >
        <fieldset class="adminform">
            <legend> <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_MAPA_TITLE') ?> </legend>
            <div id="mapa_propuestasdiv" style="width:100%; height:400px ">
            </div>

        </fieldset>
    </div>
</div>

<div class="width-50 fltrt" >
    <div id="imgUbicacionGeografica" style=" 
         background: url(images/logo_default.jpg);
         background-size: contain;
         background-repeat: no-repeat;
         background-position: center center;
         width: 100%;
         height: 213px;"
         >
    </div>
    <div id="frmUbicacionGeografica" class ="hide"> 
        <div id="frmTipoGraficoUG" >
            <fieldset class="adminform">
                <legend> <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_FORMA_GRAFICO_TITLE') ?> </legend>
                <form id="frmDtaUbcGeo" >
                    <ul class="adminformlist">
                        <?php foreach ($this->form->getFieldset('formaGrafico') as $field): ?>
                        <li>
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="clr"></div>
                    <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                        <input id="btnAddLstCoordenadas" type="button" value="<?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_ADD_LSTCOORDENADA_TITLE') ?>">
                    <?php endIf; ?>

                    <input id="btnCancelarLstCoor" type="button" value="<?php echo JText::_('BTN_LIMPIAR') ?>">
                    <div class="clr"></div>
                </form>
            </fieldset>
        </div>
        <!-- Registro de vertices de un determinado Grafico -->
        <div id="frmCoordenadasUG" class ="hide">
            <fieldset class="adminform">
                <legend> <?php echo JText::_('COM_CANASTAPROY_FIELD_PROYECTO_PUNTOS_GRAFICO_TITLE') ?> </legend>
                <!--Coordenadas LAT LONG-->
                <form id="frmDtaUbcGeoCoordenada" >
                    <ul>
                        <?php foreach ($this->form->getFieldset('coordenadasGrafico') as $field): ?>
                        <li>
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <!--Solo para el circulo-->
                    <ul id="obras-radio" style="display:none">
                        <?php foreach ($this->form->getFieldset('radioCirculo') as $field): ?>
                        <li>
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="clr"></div>
                    <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                        <input id="btnAddCoordenada" type="button" value="<?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_ADD_COORDENADA_TITLE') ?>" >
                    <?php endIf; ?>

                    <input id="btnLimpiarGrafico" type="button" value="<?php echo JText::_('BTN_LIMPIAR') ?>">
                    <div class="clr"></div>
                </form>
                <ul>
                    <li id="puntos-table">
                        <table id="tbLstPuntos" width="100%" class="tablesorter" cellspacing="1">
                            <thead>
                                <tr>
                                    <th align="center"> <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_LATITUD_LABEL') ?> </th>
                                    <th align="center"> <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_LONGITUD_LABEL') ?> </th>
                                    <th colspan="3" align="center"> <?php echo JText::_('COM_CANASTAPROY_FIELD_INDICADOR_OPERACION_FUENTE') ?> </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </li>
                    <li id="radio-table" style=" display: none">
                        <table id="tbLstCirculo" width="100%" class="tablesorter" cellspacing="1">
                            <thead>
                                <tr>
                                    <th align="center"> <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_LATITUD_LABEL') ?> </th>
                                    <th align="center"> <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_LONGITUD_LABEL') ?> </th>
                                    <th align="center"> <?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_RADIO_LABEL') ?> </th>
                                    <th colspan="3" align="center"> <?php echo JText::_('COM_CANASTAPROY_FIELD_INDICADOR_OPERACION_FUENTE') ?> </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </li>
                </ul>
                <div class="width-45 fltrt"></div>
                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                    <input id="btnAddGrafico" type="button" value="<?php echo JText::_('COM_CANASTAPROY_FIELD_PROPUESTA_ADD_GRAFICO_TITLE') ?>">
                <?php endif; ?>

                <input id="btnLimpiarPuntos" type="button" value="<?php echo JText::_('BTN_LIMPIAR') ?>">
                <div class="clr"></div>
            </fieldset>
        </div>
    </div>
</div>
