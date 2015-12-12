<?php 
$hooks = $menu = $inputs = "";
if(isset($options_machine)){
    $hooks = json_encode(of_get_header_classes_array());
    $menu = $options_machine->Menu;
    $inputs = $options_machine->Inputs;
    $nonce = "of_ajax_nonce";
    $class = "theme_options";
}
elseif(isset($seo_machine)){
    $hooks = json_encode(seo_of_get_header_classes_array());
    $menu = $seo_machine->Menu;
    $inputs = $seo_machine->Inputs;
    $nonce = "seo_of_ajax_nonce";
    $class = "seo_options";
}
elseif(isset($comp_machine)){
    $hooks = json_encode(comp_of_get_header_classes_array());
    $menu = $comp_machine->Menu;
    $inputs = $comp_machine->Inputs;
    $nonce = "comp_of_ajax_nonce";
    $class = "comp_options";
}
?>
<div class="wrap <?php echo $class;?>" id="of_container">

	<div id="of-popup-save" class="of-save-popup">
		<div class="of-save-save">Options Updated</div>
	</div>
	
	<div id="of-popup-reset" class="of-save-popup">
		<div class="of-save-reset">Options Reset</div>
	</div>
	
	<div id="of-popup-fail" class="of-save-popup">
		<div class="of-save-fail">Error!</div>
	</div>
	
	<span style="display: none;" id="hooks">
            <?php echo $hooks;?>
        </span>
	<input type="hidden" id="reset" value="<?php if(isset($_REQUEST['reset'])) echo $_REQUEST['reset']; ?>" />
	<input type="hidden" id="security" name="security" value="<?php echo wp_create_nonce($nonce); ?>" />

	<form id="of_form" method="post" action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ) ?>" enctype="multipart/form-data" >
	
		<div id="header">
		
			<div class="logo">
				<h2><?php echo THEMENAME; ?></h2>
				<span><?php echo ('v'. THEMEVERSION); ?></span>
			</div>
		
			<div id="js-warning">Warning- This options panel will not work properly without javascript!</div>
			<div class="icon-option"></div>
			<div class="clear"></div>
		
                </div>

		<div id="info_bar">
		
			<a>
				<div id="expand_options" class="expand">Expand</div>
			</a>
						
			<img style="display:none" src="<?php echo ADMIN_DIR; ?>assets/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />

			<button id="of_save" type="button" class="button-primary">
				<?php _e('Save All Changes' ,'themeton');?>
			</button>
			
		</div><!--.info_bar--> 	
		
		<div id="main">
		
			<div id="of-nav">
				<ul>
                                    <?php echo $menu;?>
				</ul>
			</div>

			<div id="content">
		  		<?php echo $inputs;?>
		  	</div>
		  	
			<div class="clear"></div>
			
		</div>
		
		<div class="save_bar"> 
		
			<img style="display:none" src="<?php echo ADMIN_DIR; ?>assets/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
			<button id ="of_save" type="button" class="button-primary"><?php _e('Save All Changes' ,'themeton');?></button>			
			<button id ="of_reset" type="button" class="button submit-button reset-button" ><?php _e('Options Reset' ,'themeton');?></button>
			<img style="display:none" src="<?php echo ADMIN_DIR; ?>assets/images/loading-bottom.gif" class="ajax-reset-loading-img ajax-loading-img-bottom" alt="Working..." />
			
		</div><!--.save_bar--> 
 
	</form>
	
	<div style="clear:both;"></div>

</div><!--wrap-->