<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<div class="width-100 fltlft">
    <fieldset class="adminform">
        <legend> <?php echo JText::_('COM_UNIDAD_GESTION_FIELD_LST_OBJETIVOS_UG_LABEL') ?> </legend>
        <!-- Gestiona los Objetivos de un Poa de una Unidad de Gestion -->
        <div class="m" id="ugObjetivos">
            <table id="tbLstObjetivosUG" width="100%" class="tablesorter" cellspacing="1">
                <thead>
                    <tr>
                        <th align="center"><?php echo JText::_('COM_UNIDAD_GESTION_FIELD_OBJETIVO_DESCRIPCION_LABEL') ?></th>
                        <th align="center"><?php echo JText::_('COM_UNIDAD_GESTION_FIELD_OBJETIVO_PRIORIDAD_LABEL') ?></th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($this->items) > 0): ?>
                        <?php foreach ($this->items as $objetivo): ?>
                            <?php $reg = $objetivo->registroObj ?>
                            <tr id="<?php echo $reg; ?>">
                                <td align="left"> <?php echo $objetivo->descObjetivo; ?> </td>
                                <td align="center"> <?php echo $objetivo->nmbPrioridadObj; ?> </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>                                    

                </tbody>
            </table>
        </div>
    </fieldset>
</div>

