<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_( 'behavior.tooltip' );
JHtml::_( 'behavior.formvalidation' );

?>

<div id="toolbar-box">
    <div class="m">
        <?php echo $this->getToolbar(); ?>
        <div class="pagetitle icon-48-contact">
            <h2> <?php echo $this->title; ?> </h2>
        </div>
    </div>
</div>

<div id="element-box" style="padding-left: 10px; padding-top: 0px;">

    <!-- Formulario de Registro de atributos de un indicador -->
    <form id="frmTableau" name="frmTableau">
        <div class="icon">
            <div class="cpanel">
                <iframe src="<?php echo $this->_ticketTableu;?>?:embed=yes&:customViews=no&:tabs=no&:toolbar=no&:refresh" width="975px" height="395px" frameborder='0'></iframe>
            </div>
        </div>

        <div>
            <input type="hidden" name="task" value="tableu.edit" />
        </div>
    </form>

</div>
