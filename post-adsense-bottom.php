<?php

global $data;
if (!empty($data['adsense']) && is_array($data['adsense'])) {
  
    foreach ($data['adsense'] as $ads) {
        if ($ads['position'] == 'bottomcontent') {
            echo '<div id="post-bottom-ads" class="adsense" style="padding:30px 0">';
            echo '<center>';
            echo $ads['title'];
            echo '</center></div>';
            break;
        }
    }
}
?>
