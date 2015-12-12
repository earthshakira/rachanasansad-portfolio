<?php
global $blogConf;
if(isset($blogConf['sidebar_position'])){ ?>
    <div class="widgets-container">
        <div class="masonry-widgets"><?php
            if(!dynamic_sidebar(isset($blogConf['sidebar']) ? $blogConf['sidebar'] : 'default-sidebar')) {
                print '<aside id="archives" class="widget">';
                print 'There is no widget. You should add your widgets into <strong>';
                print isset($blogConf['sidebar']) ? $blogConf['sidebar'] : 'Default';
                print '</strong> sidebar area on <strong>Appearance => Widgets</strong> of your dashboard. <br/><br/>';
                print '</aside>';?>
                <aside id="archives" class="widget">
                    <h3 class="widget-title"><?php _e('Archives', 'themeton'); ?></h3>
                    <div class="widget-content">
                        <ul>
                            <?php wp_get_archives(array('type' => 'monthly')); ?>
                        </ul>
                    </div>
                </aside><?php
            } ?>
        </div>
    </div><?php
}