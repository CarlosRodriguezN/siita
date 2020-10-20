<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<body text="#000000">
    <table cellspacing="0" cols="5" border="0">
        <colgroup width="272"></colgroup>
        <colgroup width="59"></colgroup>
        <colgroup width="99"></colgroup>
        <colgroup width="265"></colgroup>
        <colgroup width="124"></colgroup>

        <!-- TITULO -->
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="46" align="center" valign=middle sdnum="12298;12298;estandar">
                <font color="#000000"><br>
                    <img src="media/system/images/ECORAE icons/SNI.jpg" width=77 height=36 hspace=97 vspace=5>
                </font>
            </td>
            
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="center" valign=middle sdnum="12298;12298;estandar">
                <b><font size=4 color="#000000"> <?php echo JText::_( 'COM_PROYECTOS_FICHA_METODOLOGICA' ); ?> </font></b>
            </td>
            
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom sdnum="12298;12298;estandar">
                <font color="#000000"><br></font>
            </td>
        </tr>
        
        <!-- CLASIFICADOR TEMATICO -->
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 height="20" align="left" valign=bottom sdnum="12298;12298;estandar">
                <b><font color="#000000"> <?php echo JText::_( 'COM_PROYECTOS_CLASIFICADOR_TEMATICO' ); ?> </font></b>
            </td>

            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=bottom sdnum="12298;12298;estandar">
                <font color="#000000"> <?php echo JText::_( 'COM_PROYECTOS_USO_INTERNO' ); ?> </font>
            </td>
            
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom sdnum="12298;12298;estandar">
                <font color="#000000"> <?php echo JText::_( 'COM_PROYECTOS_USO_INTERNO' ); ?> </font>
            </td>
        </tr>

        <!-- CLASIFICADOR SECTORIAL -->
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 height="20" align="left" valign=bottom sdnum="12298;12298;estandar">
                <b><font color="#000000"> <?php echo JText::_( 'COM_PROYECTOS_CLASIFICADOR_SECTORIAL' ); ?> </font></b>
            </td>

            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=bottom sdnum="12298;12298;estandar">
                <font color="#000000"> <?php echo JText::_( 'COM_PROYECTOS_USO_INTERNO' ); ?> </font>
            </td>

            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom sdval="1005" sdnum="12298;12298;estandar">
                <font color="#000000"> <?php echo JText::_( 'COM_PROYECTOS_USO_INTERNO' ); ?> </font>
            </td>
        </tr>

        <!-- ELABORADO POR -->
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 height="20" align="left" valign=bottom sdnum="12298;12298;estandar">
                <b><font color="#000000"> <?php echo JText::_( 'COM_PROYECTOS_ELABORADO_POR' ); ?> </font></b>
            </td>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="center" valign=bottom sdnum="12298;12298;estandar">
                <font color="#000000"> <?php echo JText::_( 'COM_PROYECTOS_ECORAE' ); ?> </font>
            </td>
        </tr>
        
        <!-- NOMBRE DEL INDICADOR O VARIABLE -->
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 height="20" align="left" valign=bottom sdnum="12298;12298;estandar">
                <b><font color="#000000"> <?php echo JText::_( 'COM_PROYECTOS_NOMBRE_INDICADOR' ); ?> </font></b>
            </td>
            
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="center" valign=bottom sdnum="12298;12298;estandar">
                <b><font color="#000000"> <?php echo $this->_items["nombreIndicador"]; ?> </font></b>
            </td>
        </tr>
        
        <!-- DEFINICION -->
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 rowspan=4 height="80" align="center" valign=middle sdnum="12298;12298;estandar">
                <b><font color="#000000"> <?php echo JText::_( 'COM_PROYECTOS_DEFINICION' ); ?> </font></b>
            </td>
            
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 rowspan=4 align="left" valign=top sdnum="12298;12298;estandar">
                <font color="#000000"> <?php echo $this->_items["descripcion"]; ?> </font>
            </td>
        </tr>
        
        <tr>
        </tr>
        <tr>
        </tr>
        <tr>
        </tr>

        <!-- FORMA DE CALCULO -->        
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=5 height="20" align="center" valign=bottom sdnum="12298;12298;estandar">
                <b><font color="#000000"><?php echo JText::_( 'COM_PROYECTOS_FORMULA' ); ?></font></b>
            </td>
        </tr>

        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=5 height="20" align="left" valign=bottom sdnum="12298;12298;estandar">
                <font color="#000000">donde:</font>
            </td>
        </tr>
        
        <!-- UNIDAD DE MEDIDA -->
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 height="20" align="left" valign=bottom sdnum="12298;12298;estandar">
                <b><font color="#000000"> <?php echo JText::_( 'COM_PROYECTOS_UNIDAD_MEDIDA' ); ?> </font></b>
            </td>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="left" valign=bottom sdnum="12298;12298;estandar">
                <font color="#000000"><?php echo $this->_items["undMedida"]; ?></font>
            </td>
        </tr>
        
        <!-- EXPRESION -->
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 height="20" align="left" valign=bottom sdnum="12298;12298;estandar">
                <b><font color="#000000"><?php echo JText::_( 'COM_PROYECTOS_EXPRESION' ); ?></font></b>
            </td>

            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="left" valign=bottom sdnum="12298;12298;estandar">
                <font color="#000000"> <?php echo $this->_items["undAnalisis"]; ?> </font>
            </td>
        </tr>

        <!-- VARIABLES ASOCIADAS -->
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=5 height="20" align="center" valign=bottom sdnum="12298;12298;estandar">
                <b><font color="#000000"><?php echo JText::_( 'COM_PROYECTOS_VARIABLES_ASOCIADAS' ); ?></font></b>
            </td>
        </tr>
        
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=5 height="20" align="center" valign=bottom sdnum="12298;12298;estandar">
                <table cellspacing="0" border="0">
                    <?php foreach ( $this->_items["lstVariables"] as $variable ):?>
                    <tr>
                        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=5 height="60" align="left" valign=top sdnum="12298;12298;@">
                            <b> <font color="#000000"> <?php echo $variable->nombre; ?> </font> </b>
                        </td>

                        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=5 height="60" align="left" valign=top sdnum="12298;12298;@">
                            <font color="#000000"> <?php echo $variable->descripcion; ?> </font>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </table>
            </td>
        </tr>
        
        <!-- FUENTE DE DATOS - LINEAS BASE -->        
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 height="120" align="left" valign=middle sdnum="12298;12298;estandar">
                <b><font color="#000000"><?php echo JText::_( 'COM_PROYECTOS_FUENTE_DATOS' ); ?></font></b>
            </td>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="left" valign=top sdnum="12298;12298;estandar">
                <font color="#000000"> <?php echo JText::_( 'COM_PROYECTOS_ECORAE' ); ?> </font>
            </td>
        </tr>
        
        <!-- PERIODICIDAD -->
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 height="20" align="left" valign=bottom sdnum="12298;12298;estandar">
                <b><font color="#000000"><?php echo JText::_( 'COM_PROYECTOS_PERIODICIDAD' ); ?></font></b>
            </td>
            
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="left" valign=bottom sdnum="12298;12298;estandar">
                <font color="#000000"><?php echo $this->_items["frcMonitoreo"] ; ?></font>
            </td>
        </tr>

        <!-- DISPONIBILIDAD EN EL SISTEMA -->
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 height="20" align="left" valign=bottom sdnum="12298;12298;estandar">
                <b><font color="#000000"><?php echo JText::_( 'COM_PROYECTOS_DISPONIBILIDAD_SISTEMA' ); ?></font></b>
            </td>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="left" valign=bottom sdnum="12298;12298;estandar">
                <font color="#000000"><?php echo JText::_( 'COM_PROYECTOS_NO_DEFINIDO' ); ?></font>
            </td>
        </tr>
        
        <!-- NIVEL DE DESAGREGACION -->
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=5 height="20" align="center" valign=bottom sdnum="12298;12298;estandar">
                <b><font color="#000000"><?php echo JText::_( 'COM_PROYECTOS_NIVEL_DESAGREGACION' ); ?></font></b>
            </td>
        </tr>

        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 height="20" align="left" valign=bottom sdnum="12298;12298;estandar">
                <b><font color="#000000"><?php echo JText::_( 'COM_PROYECTOS_GEOGRAFICO' ); ?></font></b>
            </td>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="left" valign=bottom sdnum="12298;12298;estandar">
                <font color="#000000"><?php echo JText::_( 'COM_PROYECTOS_NACIONAL' ); ?></font>
            </td>
        </tr>

        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 height="20" align="left" valign=bottom sdnum="12298;12298;estandar">
                <b><font color="#000000"><?php echo JText::_( 'COM_PROYECTOS_GENERAL' ); ?></font></b>
            </td>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="left" valign=bottom sdnum="12298;12298;estandar">
                <font color="#000000"><?php echo JText::_( 'COM_PROYECTOS_NO_APLICA' ); ?></font>
            </td>
        </tr>

        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 height="20" align="left" valign=bottom sdnum="12298;12298;estandar">
                <b><font color="#000000"><?php echo JText::_( 'COM_PROYECTOS_OTROS_AMBITOS' ); ?></font></b>
            </td>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="left" valign=bottom sdnum="12298;12298;estandar">
                <font color="#000000"><?php echo JText::_( 'COM_PROYECTOS_NO_APLICA' ); ?></font>
            </td>
        </tr>

        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=5 height="20" align="center" valign=bottom sdnum="12298;12298;estandar">
                <b><font color="#000000">nota técnica</font></b>
            </td>
        </tr>
        
        <!-- NOTA TECNICA -->
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=5 height="101" align="left" valign=middle sdnum="12298;12298;estandar">
                <font color="#000000"> <?php echo JText::_( 'COM_PROYECTOS_NOTA_TECNICA' );?> </font>
            </td>
        </tr>
        
        <!-- SINTAXIS -->
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=5 height="20" align="center" valign=bottom sdnum="12298;12298;estandar">
                <b><font color="#000000"><?php echo JText::_( 'COM_PROYECTOS_SINTAXIS' );?></font></b>
            </td>
        </tr>
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=5 height="20" align="center" valign=bottom sdnum="12298;12298;estandar">
                <font color="#000000"> <?php echo JText::_( 'COM_PROYECTOS_NO_APLICA' );?> </font>
            </td>
        </tr>
        

        <!-- FUENTE DE GENERACION -->
        <tr>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=4 height="20" align="left" valign=bottom sdnum="12298;12298;estandar">
                <b><font color="#000000"> <?php echo JText::_( 'COM_PROYECTOS_FUENTE_GENERACION' );?> </font></b>
            </td>
            <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom sdnum="12298;12298;mmm-aa">
                <font color="#000000"><?php echo JText::_( 'COM_PROYECTOS_ECORAE' ); ?></font>
            </td>
        </tr>
    </table>

</body>