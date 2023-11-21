<?php
$context = stream_context_create([
    'ssl' => [
        'verify_peer'       => false,
        'verify_peer_name'  => false,
    ]
]);
file_put_contents('C:\Users\Administrator\Downloads\dy\test2.jpeg', file_get_contents('https://p3-pc-sign.douyinpic.com/tos-cn-i-0813/ok2UBOcDAw9vAIFEGCelABBngfCFAAlwbAVAgM~tplv-dy-water-v2:5oqW6Z-z5Y-377yaMzA3MDYyOTIyNDc=:1440:2564.webp?x-expires=1703138400&x-signature=%2BPomi6pua9XJy%2F6QpEwLh0QgYqk%3D&sig=z9lqPpNANRHvcO19gwE0Twg0v0Q%3D&from=3213915784&s=PackSourceEnum_PUBLISH&se=false&sc=image&biz_tag=aweme_images&l=20231121143555821FCEE871EB09037A70', false, $context));