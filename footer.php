<?php global $data; ?>
    <?php if(isset($data['show_footer'])&&$data['show_footer'] ){ ?>
        <!-- START FOOTER-->
        <footer id="footer">
            <div class="footer-sidebar-container">
                <div class="container"><div class="row">
				<?php
                        global $footerGrid, $data;
                        $grid = (isset($data['footer_layout'])&&$data['footer_layout'] != '') ? $data['footer_layout']:'4';
                        $grid = $footerGrid[$grid];
                        $i = 1;
                        foreach (split('-', $grid) as $g) {
                            echo "<div class='span$g'>";
                            dynamic_sidebar("footer-sidebar-$i");
                            $i++;
                            echo "</div>";
                        } ?>
				</div></div>
				</div>
        </footer>
        <!-- END FOOTER -->
    <?php } ?>
        <div class="sub-footer">
            <div class="container"><div class="row">
                    <div class="span12">
                        <div class="copyright"><?php echo $data['copyrighttext']; ?></div>
                        <div class="footer-bottom-menu"><?php footer_navigation(); ?></div>
                    </div>
            </div></div>
        </div>
</div>
<script>
$(document).ready(function(){
    $('ul#filters li a').on('click',function(){
      $('ul#top li a').removeClass('selected');
    });

    $('ul#top li a').on('click',function(){
      $('ul#filters li a').removeClass('selected');
    });
      var url=window.location.href;
      var list = document.getElementsByTagName("a");
      for(var i=0;i<list.length;i++)
      {
        if(list[i].href==url)
        {
          list[i].className = list[i].className + " this-page";
        }
      }
});


</script>
<!-- End Wrapper -->
<?php
//Google Analytics Code
    if ($data['google_analytics']){
    echo stripslashes($data['google_analytics']);
    } ?>

<?php wp_footer(); ?>
</body>
</html>
