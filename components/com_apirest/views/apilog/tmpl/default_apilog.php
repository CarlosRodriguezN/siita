<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<div>
    <!--Lista de actividades-->
    <div class="width-100" >

        <legend> <?php echo JText::_('COM_APIREST_DOCUMENTOS') ?> </legend>
            <div class="clr"></div>

            <table id="lstDocumentos" width="100%" class="tablesorter" cellspacing="1">
                <thead>
                    <tr>
                        <th align="center"> <?php echo JText::_('COM_APIREST_FECHA') ?> </th>
                        <th align="center"> <?php echo JText::_('COM_APIREST_HORA') ?> </th>
                        <th align="center"> <?php echo JText::_('COM_APIREST_IP_ACCESO') ?> </th>
                        <th align="center"> <?php echo JText::_('COM_APIREST_MENSAJE') ?> </th>
                    </tr>
                </thead>

                <tbody>
                    <?php   $num = count( $this->_dtaLog );
                            if( count( $num ) ): 
                                for( $x = $num-1; $x > 4; $x-- ):
                                    $dtaLog = explode( ';', $this->_dtaLog[$x] ); ?>
                    <tr>    
                        <td><?php echo $dtaLog[0] ?></td>
                        <td><?php echo $dtaLog[1] ?></td>
                        <td><?php echo $dtaLog[2] ?></td>
                        <td><?php echo $dtaLog[3] ?></td>    
                    </tr>
                    <?php       endfor; ?>
                    <?php   else: ?>
                    <tr>
                        <td colspan="4" align="center"><?php echo JText::_( 'COM_APIREST_SIN_REGISTROS' ) ?></td>
                    </tr>                    
                    <?php endif;?>
                </tbody>
            </table>
        
    </div>

</div>