<?php
    // No direct access to this file
    defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<table class="change_order_items">
    <!-- Nombre del Indicador -->
    <tr class="even_row">
        <td style="text-align: left" colspan="2"><?php echo JText::_( 'COM_REPORTES_NOMBRE_INDICADOR' );?> </td>
        <td> <strong> <?php echo htmlentities( $this->_dtaIndicador->nombreIndicador );  ?> </strong> </td>
    </tr>

    <!-- Definicion del Indicador -->
    <tr class="odd_row">
        <td style="text-align: left" colspan="2"><?php echo JText::_( 'COM_REPORTES_DEFINICION' );?> </td>
        <td> <strong> <?php echo htmlentities( $this->_dtaIndicador->descripcion ); ?> </strong> </td>
    </tr>
    
    <!-- Formula -->
    <tr class="even_row">
        <td colspan="3" style="text-align: center;"><?php echo JText::_( 'COM_REPORTES_FORMULA' );?> </td>
    </tr>
    <tr class="odd_row">
        
        <td colspan="3">
            <table class="change_order_items">
                <tr>
                    <td style="text-align: center;" colspan="2"> <?php echo $this->_dtaIndicador->formula; ?> </td>
                </tr>

                <?php if( count( $this->_dtaIndicador->lstVariables ) ): ?>
                <tr>
                    <td  style="text-align: left;" colspan="2"> <?php echo JText::_( 'COM_REPORTES_DONDE' ) ?> </td>
                </tr>
                
                <?php   foreach( $this->_dtaIndicador->lstVariables as $variable ): ?>
                <tr>
                    <td><?php echo htmlentities( $variable->alias ); ?></td>
                    <td><?php echo htmlentities( $variable->nombre ); ?></td>
                </tr>
                
                <?php   endforeach; ?>
                <?php else:?>

                <tr>
                    <td><?php echo JText::_( 'COM_REPORTES_NO_APLICA' ); ?></td>
                </tr>

                <?php endif; ?>
            </table>
        </td>

    </tr>
    
    <!-- Definicion del indicador -->
    <tr class="even_row">
        <td colspan="3"><?php echo JText::_( 'COM_REPORTES_DEFINICION_VARIABLES' );?> </td>
    </tr>
    
    <!-- Variables del indicador -->
    <tr class="odd_row">
        <td colspan="3"> 
            <table class="change_order_items">    
                <?php if( count( $this->_dtaIndicador->lstVariables ) ): ?>
                <?php $class = "odd_row"; ?>
                <?php   foreach( $this->_dtaIndicador->lstVariables as $variable ): ?>

                <?php       if( strlen( $variable->descripcion ) > 0 ): ?>
                <?php           $class = ( $class == "odd_row" )? "even_row"
                                                                : "odd_row"; ?>

                <tr class="<?php echo $class; ?>">
                    <td><?php echo htmlentities( $variable->descripcion ); ?></td>
                </tr>

                <?php       endif; ?>
                <?php   endforeach; ?>
                <?php endif; ?>
            </table>
            
        
        </td>
    </tr>
    
    <!-- Metodologia de Calculo del indicador -->
    <tr class="even_row">
        <td colspan="3"><?php echo JText::_( 'COM_REPORTES_METODOLOGIA_CALCULO' );?> </td>
    </tr>
    <tr class="odd_row">
        <td colspan="3"> <strong> <?php echo htmlentities( $this->_dtaIndicador->metodologia ); ?> </strong> </td>
    </tr>
    
    <!-- Limitiaciones Tecnicas -->
    <tr class="even_row">
        <td colspan="3"><?php echo JText::_( 'COM_REPORTES_LIMITACIONES_TECNICAS' );?> </td>
    </tr>
    <tr class="odd_row">
        <td colspan="3"> <strong> <?php echo htmlentities( $this->_dtaIndicador->limitaciones ); ?> </strong> </td>
    </tr>
    
    <!-- VALOR META -->
    <tr class="even_row">
        <td colspan="2"><?php echo JText::_( 'COM_REPORTES_VALOR_META' );?> </td>
        <td> <strong> <?php echo $this->_dtaIndicador->umbral; ?> </strong> </td>
    </tr>
    
    <!-- UNIDAD DE MEDIDA O EXPRESION DEL INDICADOR -->
    <tr class="odd_row">
        <td colspan="2"><?php echo JText::_( 'COM_REPORTES_UNIDAD_MEDIDA' );?> </td>
        <td> <strong> <?php echo $this->_dtaIndicador->undMedida; ?> </strong> </td>
    </tr>
    
    <!-- INTERPRETACION DEL INDICADOR -->
    <tr class="even_row">
        <td colspan="2"><?php echo JText::_( 'COM_REPORTES_INTERPRETACION_INDICADOR' );?> </td>
        <td> <strong> <?php echo htmlentities( $this->_dtaIndicador->interpretacion ); ?> </strong> </td>
    </tr>
    
    <!-- FUENTES DE DATOS -->
    <tr class="odd_row">
        <td colspan="2"><?php echo JText::_( 'COM_REPORTES_FUENTE_DATOS' );?> </td>
        <td> <?php echo ''; ?> </td>
    </tr>
    
    <!-- PERIODICIDAD DEL INDICADOR Y/O LAS VARIABLES -->
    <tr class="even_row">
        <td colspan="2"><?php echo JText::_( 'COM_REPORTES_PERIODICIDAD' );?> </td>
        <td> <strong> <?php echo $this->_dtaIndicador->frecuenciaMonitoreo; ?> </strong> </td>
    </tr>
    
    <!-- DISPONIBILIDAD DE LOS DATOS -->
    <tr class="odd_row">
        <td colspan="2"><?php echo JText::_( 'COM_REPORTES_DISPONIBILIDAD_SISTEMA' );?> </td>
        <td> <strong> <?php echo $this->_dtaIndicador->disponibilidad; ?> </strong> </td>
    </tr>
    
    <!-- NIVEL DE DESAGREGACION -->
    <tr class="even_row">
        <td rowspan="3"><?php echo JText::_( 'COM_REPORTES_NIVEL_DESAGREGACION' );?> </td>
        <td><?php echo JText::_( 'COM_REPORTES_GEOGRAFICO' );?> </td>
        <td> <strong> <?php echo $this->_dtaIndicador->lstUndTerritorial; ?> </strong> </td>
    </tr>
    
    <!-- REPORTES GENERAL -->
    <tr class="odd_row">
        <td><?php echo JText::_( 'COM_REPORTES_GENERAL' );?> </td>
        <td> <strong> <?php echo $this->_dtaIndicador->lstDimensiones; ?> </strong> </td>
    </tr>
    
    <!-- OTROS AMBITOS -->
    <tr class="even_row">
        <td><?php echo JText::_( 'COM_REPORTES_OTROS_AMBITOS' );?> </td>
        <td> <?php echo JText::_( 'COM_REPORTES_NO_APLICA' ); ?> </td>
    </tr>
    
    <!-- INFORMACION DE GEO REFERENCIADA -->
    <tr class="odd_row">
        <td colspan="2"><?php echo JText::_( 'COM_REPORTES_INFORMACION_GEO' );?> </td>
        <td> <?php echo ''; ?> </td>
    </tr>
    
    <!-- RELACI&Oacute;N CON INSTRUMENTOS DE PLANIFICACI&Oacute;N NACIONAL E INTERNACIONAL -->
    <tr class="even_row">
        <td colspan="2"><?php echo JText::_( 'COM_REPORTES_RELACION' );?> </td>
        <td> <?php echo ''; ?> </td>
    </tr>
    
    <!-- REFERENCIAS BIBLIOGRAFICAS -->
    <tr class="odd_row">
        <td colspan="2"><?php echo JText::_( 'COM_REPORTES_REFERENCIAS_BIBLIOGRAFICAS' );?> </td>
        <td> <?php echo ''; ?> </td>
    </tr>
    
    <!-- FECHA DE CREACION -->
    <tr class="even_row">
        <td colspan="2"><?php echo JText::_( 'COM_REPORTES_FECHA_CREACION' );?> </td>
        <td> <strong> <?php echo $this->_dtaIndicador->fechaElaboracion; ?> <strong> </td>
    </tr>
    
    <!-- FECHA ULTIMA ACTUALIZACION -->
    <tr class="odd_row">
        <td colspan="2"><?php echo JText::_( 'COM_REPORTES_FECHA_ULTIMA_UPD' );?> </td>
        <td> <strong> <?php echo $this->_dtaIndicador->fechaModificacion; ?> </strong> </td>
    </tr>
    
    <!-- CLASIFICADOR SECTORIAL -->
    <tr class="even_row">
        <td colspan="2"><?php echo JText::_( 'COM_REPORTES_CLASIFICADOR_SECTORIAL' );?> </td>
        <td> <?php echo ''; ?> </td>
    </tr>
    
    <!-- ELABORADO POR -->
    <tr class="odd_row">
        <td colspan="2"><?php echo JText::_( 'COM_REPORTES_ELABORADO_POR' );?> </td>
        <td> <strong> <?php echo JText::_( 'COM_REPORTES_ECORAE' ); ?> </strong> </td>
    </tr>

</table>