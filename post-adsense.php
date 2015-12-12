<?php

global $data;
if (!empty($data['adsense']) && is_array($data['adsense'])) {
  
    foreach ($data['adsense'] as $ads) {
        if ($ads['position'] == 'abovecontent') {
            echo '<div id="post-top-ads" class="adsense" style="padding-bottom:30px">';
            echo '<center>';
            echo $ads['title'];
            echo '</center></div>';
            break;
        }
    }
}
?>
