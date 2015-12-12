<?php
/**
 * SMOF Admin
 *
 * @package     WordPress
 * @subpackage  SMOF
 * @since       1.4.0
 * @author      Syamil MJ
 */
 

/**
 * Head Hook
 *
 * @since 1.0.0
 */
function of_head() { do_action( 'of_head' ); }

/**
 * Add default options upon activation else DB does not exist
 *
 * @since 1.0.0
 */
function of_option_setup()	
{
	global $of_options, $options_machine, $seo_options, $seo_machine, $comp_options, $comp_machine;
	$options_machine = new Options_Machine($of_options, 'data');
		
	if (!get_option(OPTIONS))
	{
		update_option(OPTIONS,$options_machine->Defaults);
		generate_options_css($options_machine->Defaults); //generate static css file
	}
        
	$seo_machine = new Options_Machine($seo_options, 'seo');
	if (!get_option(SEO_OPTIONS))
	{
		update_option(SEO_OPTIONS,$seo_machine->Defaults);
	}
	
	$comp_machine = new Options_Machine($comp_options, 'comp');
	if (!get_option(COMP_OPTIONS))
	{
		update_option(COMP_OPTIONS,$comp_machine->Defaults);
	}
}

/**
 * Change activation message
 *
 * @since 1.0.0
 */
function optionsframework_admin_message() { 
	
	//Tweaked the message on theme activate
	?>
    <script type="text/javascript">
    jQuery(function(){
    	
        var message = '<p>This theme comes with an <a href="<?php echo admin_url('admin.php?page=theme-options'); ?>">options panel</a> to configure settings. This theme also supports widgets, please visit the <a href="<?php echo admin_url('widgets.php'); ?>">widgets settings page</a> to configure them.</p>';
    	jQuery('.themes-php #message2').html(message);
    
    });
    </script>
    <?php
	
}

/**
 * Get header classes
 *
 * @since 1.0.0
 */
function of_get_header_classes_array() 
{
    global $of_options;	
    foreach ($of_options as $value) 
    {
        if ($value['type'] == 'heading')
            $hooks[] = str_replace(' ','',strtolower($value['name']));	
    }
    return $hooks;
}

function seo_of_get_header_classes_array() 
{
    global $seo_options;	
    foreach ($seo_options as $value) 
    {
        if ($value['type'] == 'heading')
            $hooks[] = str_replace(' ','',strtolower($value['name']));	
    }
    return $hooks;                     
}

function comp_of_get_header_classes_array() 
{
    global $comp_options;	
    foreach ($comp_options as $value) 
    {
        if ($value['type'] == 'heading')
            $hooks[] = str_replace(' ','',strtolower($value['name']));	
    }
    return $hooks;                     
}


/**
 * For use in themes
 *
 * @since forever
 */
$data = get_option(OPTIONS);
$seo = get_option(SEO_OPTIONS);
$comp = get_option(COMP_OPTIONS);