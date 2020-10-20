<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<fieldset class="adminformlist">
    <legend><?php echo JText::_('COM_PROGRAMA_IMAGEN_PROGRAMA'); ?></legend>
    <!--IMAGEN-->
    <table width="75%" style="margin: 10px;">
        <tr>
            <!--IMAGEN-->
            <td align="center">
                <input id="imgImgUpLoad" name="imgImgUpLoad" type="file" multiple="true">
                Tipo de archivo admitido para imagen .jpg/.png
            </td>
            <!--LOGO-->
            <td align="center">
                <input id="imgLogoUpload" name="imgLogoUpload" type="file" multiple="true">
                Tipo de archivo admitido para logo .png
            </td>

            <!--ICONO-->
            <td align="center">
                <input id="imgIconoUpLoad" name="imgIconoUpLoad" type="file" multiple="true">
                Tipo de archivo admitido para icono .png
            </td>

        </tr>
        <tr align="center">
            <td style="padding: 5px; border: 1px solid #aaaaaa; ">
                <?php if ($this->item->intCodigo_prg != null): ?>
                    <a class="modal" href="<?php echo $this->imagenPrg; ?>">
                        <img id="imagenPrograma" 
                             src="<?php echo $this->imagenPrg . '?num=' . rand(1, 99); ?>"
                             height="50px"
                             width="100px"/> 
                    </a>
                <?php endif; ?>
            </td>

            <!-- Logo de un Programa -->
            <td style="padding: 5px;border: 1px solid #aaaaaa;">
                <?php if ($this->item->intCodigo_prg != null): ?>
                    <a class="modal" href="<?php echo $this->logoPrg; ?>">
                        <img id="logoPrograma" 
                             src="<?php echo $this->logoPrg . '?num=' . rand(1, 99); ?>"
                             height="50px"
                             width="100px"/>
                    </a>
                <?php endif; ?>
            </td>

            <!-- Icono -->
            <td style="padding: 5px;border: 1px solid #aaaaaa;">
                <?php if ($this->item->intCodigo_prg != null): ?>
                    <a class="modal" href="<?php echo $this->iconoPrg; ?>">
                        <img id="iconoPrograma" 
                             src="<?php echo $this->iconoPrg . '?num=' . rand(1, 99); ?>"
                             height="50px"
                             width="100px"/>
                    </a>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <div class="clr"> &nbsp; </div>
</fieldset>