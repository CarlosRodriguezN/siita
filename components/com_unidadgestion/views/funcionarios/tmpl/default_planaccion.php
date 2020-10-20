<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<div class="width-100">
    <div id="accFnc">
        <?php if( !empty( $this->lstObjetivos ) ): ?>
            <?php foreach ($this->lstObjetivos as $item): ?>
                <div class="group">
                    <h3> <a href="#">  <?php echo $item->descObjetivo . " (" . count( $item->lstAcciones ) .")"; ?> </a> </h3>
                    <div class="adminform">
                        <div class="cpanel-page">
                            <div class="cpanel">
                                <?php if (!empty($item->lstAcciones)): ?>
                                    <table id="tbAcciones-<?php echo $item->registroObj; ?>" width="100%" class="tablesorter" cellspacing="1">
                                        <thead>
                                            <tr>
                                                <th align="center"><?php echo JText::_( 'COM_UNIDAD_GESTION_FIELD_ACCION_DESCRIPCION_LABEL' ) ?></th>
                                                <th align="center"><?php echo JText::_( 'COM_UNIDAD_GESTION_FIELD_ACCION_TIPO_ACCION_LABEL' ) ?></th>
                                                <th align="center"><?php echo JText::_( 'COM_UNIDAD_GESTION_FIELD_ACCION_PRESUPUESTO_LABEL' ) ?></th>
                                                <th align="center"><?php echo JText::_( 'COM_UNIDAD_GESTION_FIELD_ACCION_FECHA_INICIO_LABEL' ) ?></th>
                                                <th align="center"><?php echo JText::_( 'COM_UNIDAD_GESTION_FIELD_ACCION_FECHA_FIN_LABEL' ) ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($item->lstAcciones as $accion): ?>
                                                <tr id="">
                                                    <td align="center"> <?php echo $accion->descripcionAccion; ?> </td>
                                                    <td align="center"> <?php echo $accion->descTipoActividad; ?> </td>
                                                    <td align="center"> $<?php echo $accion->presupuestoAccion; ?> Usd </td>
                                                    <td align="center"> <?php echo $accion->fechaInicioAccion; ?> </td>
                                                    <td align="center"> <?php echo $accion->fechaFinAccion; ?> </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <?php echo JText::_( 'COM_UNIDAD_GESTION_SIN_REGISTROS' ); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>    
                </div>
            <?php endforeach; ?>
        <?php else:?>
            <div align="center"><p><?php echo JText::_( 'COM_UNIDAD_GESTION_SIN_REGISTROS' ); ?></p></div>
        <?php endif; ?>
    </div>
</div>