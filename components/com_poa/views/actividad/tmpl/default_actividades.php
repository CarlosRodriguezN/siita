<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>
<!-- lista de actividades -->
<div class="width-50 fltlft adminform" >
        <fieldset class="adminformlist">
            <legend> <?php echo JText::_( 'COM_POA_TAB_LISTA_ACTIVIDADES' ); ?> </legend>
            <div class="fltrt">  
                <input id="addActividadObjetivo" type="button" value="&nbsp;<?php echo JText::_( 'BTN_AGREGAR' ); ?>&nbsp;">
            </div>  
            <table id="lstActividadesTable" class="tablesorter">
                <thead>
                    <tr>
                        <th align="center"> <?php echo JText::_( 'COM_POA_TAB_ACTIVIDAD_TABLE_DESCRIPCION' ); ?></th>
                        <th align="center"> <?php echo JText::_( 'COM_POA_TAB_ACTIVIDAD_TABLE_RESPONDABLE' ); ?></th>
                        <th align="center"> <?php echo JText::_( 'COM_POA_TAB_ACTIVIDAD_TABLE_FCHACTIVIDAD' ); ?></th>
                        <th align="center" colspan="3"> <?php echo JText::_( 'TL_ACCIONES' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </fieldset>
</div>

<!-- formulario de las actividades -->
<div class="width-50 fltrt adminform">
    
    <!-- imagen de objetivo -->
    <div id="imgActividadObjetivoForm" class="editbackground"></div>

    <!-- Formulario de actividades  -->
    <div id="editActividadObjetivoForm" class="hide">
        <fieldset class="adminform">
            <legend><?php echo JText::_( 'COM_POA_TAB_EDIT_ACTIVIDAD' ); ?></legend>
            <ul class="adminformlist">
                <?php foreach( $this->form->getFieldset( 'actividad' ) as $field ): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
                <li>
                    <fieldset class="adminform">
                        <legend><?php echo JText::_( 'COM_POA_TAB_EDIT_FUNCIONARIO_RESPONSABLE' ); ?></legend>
                        <?php foreach( $this->form->getFieldset( 'funcionarioRes' ) as $field ): ?>
                            <?php echo $field->label; ?>
                            <?php echo $field->input; ?>
                        <?php endforeach; ?>
                    </fieldset>
                </li>
            </ul>

            <div class="clr"></div>
            <div>  
                <input id="saveActiviadad" type="button" value="&nbsp;<?php echo JText::_( 'BTN_GUARDAR' ); ?>&nbsp;">
            </div> 
            <div>  
                <input id="cancelActividad" type="button" value="&nbsp;<?php echo JText::_( 'BTN_CANCELAR' ); ?>&nbsp;">
            </div> 
        </fieldset>
    </div>
    
    <!-- Formulario de carga de archivos  -->
    <div id="editDocsActObjForm" class="hide">
        <fieldset class="adminform">
            <legend><?php echo JText::_( 'COM_POA_TAB_EDIT_ACTIVIDAD' ); ?></legend>
            <ul class="adminformlist">
                <table>
                    <tr>
                        <td><input type="file" id="uploadSon"></td>
                        <td><input id="cerrarlUpl" type="button" value="&nbsp;<?php echo JText::_( 'BTN_CERRAR' ); ?>&nbsp;"></td>
                    </tr>
                </table>               
                <li>
                    <table id="lstDocActividades"class="tablesorter">
                        <thead>
                            <tr>
                                <th align="center" > <?php echo JText::_( 'COM_POA_TAB_EDIT_DOC_ACTIVIDAD' ); ?></th>
                                <th align="center" colspan="3"> <?php echo JText::_( 'TL_ACCIONES' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </li>
            </ul>


        </fieldset>
    </div>
    
    <!-- Artibutos invisibles -->
    <div>
        <input type="hidden" id="registroObj" value="<?php echo $this->registroObj ?>">
        <input type="hidden" id="idObjetivo" value="<?php echo $this->idObjetivo ?>">
    </div>
</div>