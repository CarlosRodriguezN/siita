<?php
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted Access' );

// load tooltip behavior
JHtml::_( 'behavior.tooltip' );
?>

<div id="content-box">

    <div id="element-box">

        <div id="system-message-container"></div>

        <div class="m">
            <div class="cpanel">
                <iframe src="<?php echo $this->_ticketTableu;?>?:embed=yes&:customViews=no&:tabs=no&:toolbar=no&:refresh" width="100%" height="1000px" frameborder='0'></iframe>
            </div>
        </div>

        <div class="clr"> &nbsp; </div>

    </div>
</div>

