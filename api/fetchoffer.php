<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET'):
    // Load library
    require 'Predis/autoload.php';

    Predis\Autoloader::register();
    $redisclient = new Predis\Client();

    $arr_keys = $redisclient->keys('*');

    $arr_returnjson = [];
    foreach ($arr_keys as $offer):
        $url = $redisclient->get($offer);
        $ttl = $redisclient->ttl($offer);
        $arr_tmp = [];
        $arr_tmp['url'] = $url;
        $arr_tmp['ttl'] = $ttl;
        $arr_returnjson[$offer] = $arr_tmp;

    endforeach;
echo(json_encode($arr_returnjson));

endif; // dont act on dummy requests
?>
