<?php global $customlink; ?>
<h2 class="item-title">
    <a class="item-title-link" href="<?php echo $customlink['url']; ?>"<?php echo $customlink['target'];?> title="<?php printf(esc_attr__('%s', 'themeton'), the_title_attribute('echo=0')); ?>">
        <?php the_title(); ?>
    </a>
</h2>