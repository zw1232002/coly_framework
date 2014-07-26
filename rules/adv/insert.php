<?php
/**
 * 广告主添加验证
 */
return array(
    array(
        'field' => 'name',
        'label' => '广告主名称',
        'rules' => 'required|trim|min_length[2]|max_length[900]|htmlspecialchars'
    ),
    array(
        'field' => 'need_click_callback',
        'label' => '是否需要通知对方点击',
        'rules' => 'required|trim|htmlspecialchars'
    )
);


?>