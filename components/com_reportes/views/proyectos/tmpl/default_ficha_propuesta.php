<!-- Informacion Datos Generales -->
<table class="change_order_items">
    <tbody>

        <!-- Nombre del Indicador -->
        <tr class="even_row">
            <td style="text-align: left" colspan="2"> <?php echo JText::_( 'COM_REPORTES_NOMBRE_INDICADOR' );?> </td>
            <td> <strong> <?php echo htmlentities( $this->_dtaIndicador->nombre_del_Indicador );?> </strong> </td>
        </tr>

        <!-- Definicion -->
        <tr class="odd_row">
            <td style="text-align: left" colspan="2"> <?php echo JText::_( 'COM_REPORTES_DEFINICION' );?> </td>
            <td> <strong> <?php echo $this->_dtaIndicador->definicion;?> </strong> </td>
        </tr>

        <!-- Formula -->
        <tr class="even_row">
            <td style="text-align: left" colspan="2"> <?php echo JText::_( 'COM_REPORTES_FORMULA' );?> </td>
            <td> <strong> <?php echo $this->_dtaIndicador->formula_de_calculo["formula"];?> </strong> </td>
        </tr>

        <!-- Variables -->
        <tr class="odd_row">
            <td style="text-align: left" colspan="2"> <?php echo JText::_( 'COM_REPORTES_DEFINCION_VARIABLES' );?> </td>
            <td> 

                <table class="change_order_items">
                    <?php if ( count( $this->_dtaIndicador->formula_de_calculo["lista_de_Variables"] ) ):?>
                        <?php $estilo = "odd_row";?>
                        <?php foreach ( $this->_dtaIndicador->formula_de_calculo["lista_de_Variables"] as $variable ):?>
                            <?php
                            $estilo = ( $estilo == "even_row" )
                                    ? "odd_row"
                                    : "even_row";
                            ?>

                            <tr class="<?php echo $estilo;?>">
                                <td style="text-align: left"> <strong> <?php echo $variable["nombre"] . ' ( ' . $variable["unidad_de_Medida"] . ' )';?> </strong> </td>
                                <td> <?php echo $variable["descripcion"];?> </td>
                            </tr>

                        <?php endforeach;?>
                    <?php endif;?>
                </table>

            </td>
        </tr>

        <!-- Valor Meta -->
        <tr class="even_row">
            <td style="text-align: left" colspan="2"> <?php echo JText::_( 'COM_REPORTES_UMBRAL' );?> </td>
            <td> <strong> <?php echo $this->_dtaIndicador->valor_meta;?> </strong> </td>
        </tr>

        <!-- Unidad de Medida -->
        <tr class="odd_row">
            <td style="text-align: left" colspan="2"> <?php echo JText::_( 'COM_REPORTES_UNIDAD_MEDIDA' );?> </td>
            <td> <strong> <?php echo $this->_dtaIndicador->unidad_de_medida["unidad_medida"] . ' ( ' . $this->_dtaIndicador->unidad_de_medida["tipo_unidad_medida"] . ' )';?> </strong> </td>
        </tr>

        <!-- Fuentes de datos -->
        <tr class="even_row">
            <td style="text-align: left" colspan="2"> <?php echo JText::_( 'COM_REPORTES_FUENTE_DATOS' );?> </td>
            <td> <strong> <?php echo $this->_dtaIndicador->fuente_de_datos;?> </strong> </td>
        </tr>

        <!-- Periodicidad -->
        <tr class="odd_row">
            <td style="text-align: left" colspan="2"> <?php echo JText::_( 'COM_REPORTES_PERIODICIDAD' );?> </td>
            <td> <strong> <?php echo $this->_dtaIndicador->periodicidad_del_indicador;?> </strong> </td>
        </tr>

        <!-- Disponibilidad del Sistema -->
        <tr class="even_row">
            <td style="text-align: left" colspan="2"> <?php echo JText::_( 'COM_REPORTES_DISPONIBILIDAD_SISTEMA' );?> </td>
            <td> <strong> <?php echo $this->_dtaIndicador->disponiblidad_de_datos["fecha_de_inicio"] . ' / ' . $this->_dtaIndicador->disponiblidad_de_datos["fecha_de_fin"];?> </strong> </td>
        </tr>

        <!-- Nivel de Desagregacion -->
        <tr class="odd_row"> 
            <td style="text-align: left" rowspan="3"> <?php echo JText::_( 'COM_REPORTES_NIVEL_DESAGREGACION' );?> </td>
            
            <!-- Nivel de Desagregacion - Geografico -->
            <td style="text-align: left"> <?php echo JText::_( 'COM_REPORTES_GEOGRAFICO' );?> </td>
            <td>

                <table class="change_order_items">
                    <?php if ( count( $this->_dtaIndicador->nivel_de_desagregacion["geografico"] ) ):?>
                    
                    <tr>
                        <td align="center"> <strong> <?php echo JText::_( 'COM_REPORTES_GEOGRAFICO_PROVINCIA' ); ?> </strong> </td>
                        <td align="center"> <strong> <?php echo JText::_( 'COM_REPORTES_GEOGRAFICO_CANTON' ); ?> </strong> </td>
                        <td align="center"> <strong> <?php echo JText::_( 'COM_REPORTES_GEOGRAFICO_PARROQUIA' ); ?> </strong> </td>
                    </tr>
                    
                        <?php $estilo = "odd_row";?>
                        <?php foreach ( $this->_dtaIndicador->nivel_de_desagregacion["geografico"] as $geografico ):?>
                            <?php   $estilo = ( $estilo == "even_row" )
                                                ? "odd_row"
                                                : "even_row"; ?>

                            <tr class="<?php echo $estilo;?>">
                                <td> <?php echo $geografico["provincia"]; ?> </td>
                                <td> <?php echo $geografico["canton"];?> </td>
                                <td> <?php echo $geografico["parroquia"];?> </td>
                            </tr>

                        <?php endforeach;?>
                        <?php else: ?>
                            <tr>
                                <td align="center"> <strong> <?php echo JText::_( 'COM_REPORTES_SIN_REGISTROS' ); ?> </strong> </td>
                            </tr>
                    <?php endif;?>
                </table>

            </td>
        </tr>
        
        <tr class="even_row"> 
            <!-- Nivel de Desagregacion - General -->
            <td style="text-align: left"> <?php echo JText::_( 'COM_REPORTES_GENERAL' );?> </td>
            <td>
                <table class="change_order_items">
                    <?php if ( count( $this->_dtaIndicador->nivel_de_desagregacion["general"] ) ):?>
                    
                    <tr>
                        <td align="center"> <strong> <?php echo JText::_( 'COM_REPORTES_GENERAL_DIMENSION' ); ?> </strong> </td>
                        <td align="center"> <strong> <?php echo JText::_( 'COM_REPORTES_GENERAL_ENFOQUE' ); ?> </strong> </td>
                    </tr>
                    
                        <?php $estilo = "even_row";?>
                        <?php foreach ( $this->_dtaIndicador->nivel_de_desagregacion["general"] as $general ):?>
                            <?php   $estilo = ( $estilo == "even_row" )
                                                ? "odd_row"
                                                : "even_row"; ?>

                            <tr class="<?php echo $estilo;?>">
                                <td style="text-align: left"> <strong> <?php echo $general["dimension"]; ?> </strong> </td>
                                <td> <?php echo $general["enfoque"];?> </td>
                            </tr>

                        <?php endforeach;?>
                        <?php else: ?>
                            <tr>
                                <td align="center"> <strong> <?php echo JText::_( 'COM_REPORTES_SIN_REGISTROS' ); ?> </strong> </td>
                            </tr>
                    <?php endif;?>
                </table>
                
            </td>
        </tr>

        <tr class="odd_row">
            <!-- Otros Ambitos -->
            <td style="text-align: left"> <?php echo JText::_( 'COM_REPORTES_OTROS_AMBITOS' );?> </td>
            <td> &nbsp; </td>
        </tr>

        <!-- Relacion con instrumentos de Planificacion -->
        <tr class="even_row">
            <td style="text-align: left" colspan="2"> <?php echo JText::_( 'COM_REPORTES_RELACION' );?> </td>
            <td> 
            
                <table class="change_order_items">
                    <?php if ( count( $this->_dtaIndicador->relacion_instrumentos_de_planificacion ) ):?>
                        
                        <!-- Agenda -->
                        <tr class="even_row">
                            <td style="text-align: left" colspan="2"> <?php echo JText::_( 'COM_REPORTES_AGENDA' );?> </td>
                            <td> <strong> <?php echo $this->_dtaIndicador->relacion_instrumentos_de_planificacion["agenda"]; ?> </strong> </td>
                        </tr>
                        
                        <!-- Nivel 001 -->
                        <tr class="odd_row">
                            <td style="text-align: left" colspan="2"> <?php echo JText::_( 'COM_REPORTES_NIVEL_1' );?> </td>
                            <td> <strong> <?php echo $this->_dtaIndicador->relacion_instrumentos_de_planificacion["nivel_1"]; ?> </strong> </td>
                        </tr>
                        
                        <!-- Nivel 002 -->
                        <tr class="even_row">
                            <td style="text-align: left" colspan="2"> <?php echo JText::_( 'COM_REPORTES_NIVEL_2' );?> </td>
                            <td> <strong> <?php echo $this->_dtaIndicador->relacion_instrumentos_de_planificacion["nivel_2"]; ?> </strong> </td>
                        </tr>
                        
                        <!-- Nivel 003 -->
                        <tr class="odd_row">
                            <td style="text-align: left" colspan="2"> <?php echo JText::_( 'COM_REPORTES_NIVEL_3' );?> </td>
                            <td> <strong> <?php echo $this->_dtaIndicador->relacion_instrumentos_de_planificacion["nivel_3"]; ?> </strong> </td>
                        </tr>

                    <?php else: ?>
                        <tr>
                            <td align="center"> <strong> <?php echo JText::_( 'COM_REPORTES_SIN_REGISTROS' ); ?> </strong> </td>
                        </tr>
                    <?php endif;?>
                </table>
                
            </td>
        </tr>

        <!-- Fecha de Creacion -->
        <tr class="odd_row">
            <td style="text-align: left" colspan="2"> <?php echo JText::_( 'COM_REPORTES_FECHA_CREACION' );?> </td>
            <td> <strong> <?php echo $this->_dtaIndicador->fecha_creacion; ?> </strong> </td>
        </tr>
        
        <!-- Fecha de ultima actualizacion -->
        <tr class="even_row">
            <td style="text-align: left" colspan="2"> <?php echo JText::_( 'COM_REPORTES_FECHA_ULTIMA_UPD' );?> </td>
            <td> <strong> <?php echo $this->_dtaIndicador->fecha_modificacion ?> </strong> </td>
        </tr>

    </tbody>
</table>