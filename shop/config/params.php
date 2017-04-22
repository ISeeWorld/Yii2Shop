<?php
// 自定义参数 用于加载使用
// 2017年1月4日 20:52
return [
    'adminEmail'   => 'admin@example.com',
    // 'pageSize' => [
    //     'manage' => 10,
    //     'user'   => 10,
    //     'product' => 10,
    //     'frontproduct' => 9,
    //     'order' => 10,
    // ],
    'mypageSize'   => [
        'manage' => 6,
        'user'   => 10,
        'order'  =>6,
    ],
    'defaultValue' => [
        'avatar' => 'assets/admin/img/contact-img.png',
    ],
    'express'      => [
        0 => '',
        1 => '中通快递',
        2 => '顺丰快递',
        3 => '圆通快速',
        4 => '包邮',
    ],
    'expressPrice' => [
        0 => 0,
        1 => 15,
        2 => 20,
        3 => 18,
        4 => 0,
    ],
];
