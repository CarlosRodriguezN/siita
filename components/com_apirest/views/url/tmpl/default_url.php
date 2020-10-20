<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<div>
    <!--Lista de actividades-->
    <div class="width-50 fltlft" >
        <fieldset class="adminform">

            <legend> <?php echo JText::_('COM_APIREST_DATOS_GENERALES_TITLE'); ?> </legend>
            <ul class="adminformlist">
                <?php foreach ($this->form->getFieldset('url') as $field): ?>
                    <li>
                        <?php echo $field->label; ?>
                        <?php echo $field->input; ?>
                    </li>
                <?php endforeach; ?>
            </ul>


        </fieldset>
    </div>


    <div class="width-50 fltrt">
        <fieldset class="adminform">
            <legend> <?php echo JText::_('COM_APIREST_DOCUMENTOS') ?> </legend>
            <ul class="adminformlist">

                <div class="clr"></div>
                <input id="docUpLoad" name="docUpLoad" type="file" multiple="true">
            </ul>
            <div class="clr"></div>

            <table id="lstDocumentos" width="100%" class="tablesorter" cellspacing="1">
                <thead>
                    <tr>
                        <th align="center"> <?php echo JText::_('COM_APIREST_DOCUMENTO') ?> </th>
                        <th align="center"> <?php echo JText::_('COM_APIREST_ACCIONES') ?> </th>
                    </tr>
                </thead>

                <tbody>
                    <tr id="<?php echo $this->item->intIdApiUrl ?>">
                    <?php if( $this->_banDocumento ): ?>
                        <td><?php echo $this->item->strNombres_api. " - " .JText::_( 'COM_APIREST_AUTORIZACION' ); ?></td>
                        <td> <a class="delDocumento"> <?php echo JText::_( 'COM_APIREST_ACCION_ELIMINAR' ) ?> </a> </td>
                    <?php else: ?>
                        <td colspan="2" align="center"><?php echo JText::_( 'COM_APIREST_SIN_REGISTROS' ) ?></td>
                    <?php endif;?>
                    </tr>
                </tbody>
            </table>

        </fieldset>
    </div>

</div>