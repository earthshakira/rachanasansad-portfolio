<?php global $data;
if(is_single()){
    if(isset($data['post_comment'])&&$data['post_comment'])
        comments_template('', true);
} else {
    if(isset($data['page_comment'])&&$data['page_comment'])
        comments_template('', true);
}