<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>


<!-- Lista de objetivos -->
<div class="width-50 fltlft adminform">
    <fieldset class="adminformlist">
        <legend><?php echo JText::_( 'COM_POA_TAB_LISTA_OBJETIVOS' ); ?></legend>
        <div class="fltrt">  
            <input id="addObjetivoPoa" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_AGREGAR' ); ?> &nbsp;">
        </div>  
        <table id="lstObjetivos" class="tablesorter">
            <thead>
                <tr>
                    <th align="center"> <?php echo JText::_( 'COM_POA_TAB_OBJETIVOS_TABLE_DESCRIPCION' ); ?></th>
                    <th align="center"> <?php echo JText::_( 'COM_POA_TAB_OBJETIVOS_TABLE_PRIORIDAD' ); ?></th>
                    <th align="center"> <?php echo JText::_( 'COM_POA_TAB_OBJETIVOS_TABLE_FECHAREGSTRO' ); ?></th>
                    <th align="center" colspan="3"> <?php echo JText::_( 'COM_POA_TAB_OBJETIVOS_TABLE_COMPLEMENTOS' ); ?></th>
                    <th align="center" colspan="2"> <?php echo JText::_( 'TL_ACCIONES' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if( $this->lstObjetivos ): ?>
                    <?php foreach( $this->lstObjetivos AS $objetivo ): ?>
                        <?php $reg = $objetivo->registroObj; ?>
                        <tr id='<?php echo $reg ?>'>
                            <td><?php echo ($objetivo->descObjetivo) ? $objetivo->descObjetivo : "-----"; ?></td>
                            <td><?php echo ($objetivo->nmbPrioridadObj) ? $objetivo->nmbPrioridadObj : "-----"; ?></td>
                            <td><?php echo ($objetivo->fchRegistroObj) ? $objetivo->fchRegistroObj : "-----" ?></td>

                            <!-- Acciones de un Objetivo -->
                            <td align="center" width="15" > 
                                <a href="index.php?option=com_poa&view=plnaccion&layout=edit&registroObj=<?php echo $reg; ?>&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x: <?php echo JText::_( 'COM_PEI_POPUP_ALTO' ); ?>, y: <?php echo JText::_( 'COM_PEI_POPUP_ANCHO' ); ?>}}"> 
                                    <div id="plnaccion" >
                                        <img src="/media/com_pei/images/btnObjetivos/PA/pa_rojo_small.png">
                                    </div>
                                </a> 
                            </td>

                            <!-- Actividades de un Objetivo -->
                            <td align="center" width="15" > 
                                <a href="index.php?option=com_poa&view=actividad&layout=edit&intId_ob=<?php echo (int) $objetivo->idObjetivo; ?>&registroObj=<?php echo $reg; ?>&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x: <?php echo JText::_( 'COM_PEI_POPUP_ALTO' ); ?>, y: <?php echo JText::_( 'COM_PEI_POPUP_ANCHO' ); ?>}}"> 
                                    <div id="lstActividades" >
                                        <img src="/media/com_pei/images/btnObjetivos/PA/pa_rojo_small.png" class="hasTip" title="<?php echo JText::_( 'COM_PEI_TITLE_ACTIVIDADES' ); ?>">
                                    </div>
                                </a>
                            </td>

                            <!-- Indicadores de un Objetivo -->
                            <td align="center" width="15" >
                                <a href="index.php?option=com_poa&view=atributoindicador&layout=edit&idIndicador=1&tpoIndicador=pei&idRegObjetivo=<?php echo $reg; ?>&tmpl=component&task=preview" class="modal" rel="{handler: 'iframe', size: {x: <?php echo JText::_( 'COM_PEI_POPUP_ALTO' ); ?>, y: <?php echo JText::_( 'COM_PEI_POPUP_ANCHO' ); ?>}}"> 
                                    <div id="lstIndicadores" >
                                        <img src="/media/com_pei/images/btnObjetivos/PA/pa_rojo_small.png" class="hasTip" title="<?php echo JText::_( 'COM_PEI_TITLE_INDICADORES' ); ?>">
                                    </div>
                                </a>
                            </td>

                            <td align="center" width="15" > 
                                <a class='updObjetivo'> 
                                    <?php echo JText::_( 'LB_EDITAR' ); ?> 
                                </a> 
                            </td>
                            <td  width="15" > 
                                <a id = "delObj-<?php echo $reg ?>" class='delObjPoa'> 
                                    <?php echo JText::_( 'LB_ELIMINAR' ); ?> 
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </fieldset>
</div>


<!-- formulario de objetivos -->
<div class="width-50 fltrt">
    <div id="imgObjetivoForm" class="editbackground"></div>
    <div id="editObjetivoForm" class="hide">
        <fieldset class="adminform">
            <legend><?php echo JText::_( 'COM_POA_TAB_OBJETIVO' ); ?></legend>
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'objetivosPoa' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clr"></div>
            <div>  
                <input id="saveObjetivoPoa" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_GUARDAR' ); ?> &nbsp;">
            </div> 
            <div>  
                <input id="cancelObjetivoPoa" type="button" value=" &nbsp;<?php echo JText::_( 'BTN_CANCELAR' ); ?> &nbsp;">
            </div>  
        </fieldset>
    </div>
</div>
<div id="padre" class="hide">
    <input type="file" id="uploadFather" >
</div>
