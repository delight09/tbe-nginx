<?php
// Load library
require 'Predis/autoload.php';

Predis\Autoloader::register();
$redisclient = new Predis\Client();
$str_tbeconfdir = 'nginx_offerconf';
$str_archivesuffix = '_archived';

$arr_dircontent = array_diff(scandir($str_tbeconfdir), array('..','.'));
$arr_offeravail = $redisclient->keys('*');

// Tiddy directory contents
$arr_avilconfig_filename = [];
foreach($arr_dircontent as $e):
    $fp = join('/', array($str_tbeconfdir, $e));
    if (pathinfo($fp, PATHINFO_EXTENSION) === "conf"):
        $fn = pathinfo($fp, PATHINFO_FILENAME);
        array_push($arr_avilconfig_filename, $fn);
    endif;
endforeach;

$arr_target = array_diff($arr_avilconfig_filename, $arr_offeravail);

// Modify invalid config files
foreach($arr_target as $e):
    $str_target_file = join('/', array($str_tbeconfdir, $e)) . '.conf';
    // unlink($str_target_filename); // delete invalid offer
    // archive invalid offer
    rename($str_target_file, $str_target_file . $str_archivesuffix);
endforeach;

// Output report and reload nginx
$str_invalidoffer = '';
foreach($arr_target as $k => $v):
    $str_invalidoffer .= $v . ', ';
endforeach;
$str_invalidoffer = rtrim($str_invalidoffer, ', ');

if (empty($arr_target)):
    // echo("All clear");
else:
    echo('Offer: ' . $str_invalidoffer . ' invalid, reloading nginx!');
    shell_exec('/usr/sbin/nginx -s reload');
endif;
?>
