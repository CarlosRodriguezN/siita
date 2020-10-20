<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );
?>

<div class="width-60 fltlft" style="position: static; left: 20px; height: auto; width: 100%" >
<fieldset class="adminform">
        <legend> Lista de Poa´s </legend>
        <div class="fltrt">
            <input id="addRelacionPoaPei" type="button" value="<?php echo JText::_( 'BTN_AGREGAR' ) ?>">
        </div>
        <table id="tbLstPoas" width="100%" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"><?php echo JText::_( 'COM_PEI_FIELD_POA_DESCRIPCION_LABEL' ) ?></th>
                    <th colspan="2" align="center"><?php echo JText::_( 'ACCIONES' ) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if( $this->lstPoasPei ): ?>
                    <?php foreach( $this->lstPoasPei as $key => $objpei ): ?>
                        <tr id="<?php echo $key; ?>">
                            <td align="center"> <?php echo $objpei->strDescripcion_pi; ?> </td>
                            <td align="center" width="15" > 

                                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                    <a id = "updPoa-<?php echo $key ?>" class='updPoaPei'> 
                                        <?php echo JText::_( 'COM_PEI_FIELD_POA_UPD_LABEL' ); ?> 
                                    </a> 
                                <?php else: ?>
                                    <?php echo JText::_( 'COM_PEI_FIELD_POA_UPD_LABEL' ); ?> 
                                <?php endIf; ?>
                                
                            </td>
                            <td align="center" width="15" > 
                                <?php if( $this->canDo->get( 'core.create' ) || $this->canDo->get( 'core.edit' ) ): ?>
                                    <a id = "delPoa-<?php echo $key ?>" class='delPoaPei'> 
                                        <?php echo JText::_( 'COM_PEI_FIELD_POA_DEL_LABEL' ); ?>
                                    </a>
                                <?php else: ?>
                                    <?php echo JText::_( 'COM_PEI_FIELD_POA_DEL_LABEL' ); ?>
                                <?php endIf; ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                <?php endif; ?>                                    

            </tbody>
        </table>
    </fieldset>
</div>

