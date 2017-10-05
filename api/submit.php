<?php
// Configure
$int_ttl_min = 600;
$int_ttl_max = 36000;
$arr_schemeavil = ['http', 'https', 'ftp'];

if ($_SERVER['REQUEST_METHOD'] === 'GET'):
    // Load library
    require 'Predis/autoload.php';

    Predis\Autoloader::register();
    $redisclient = new Predis\Client();

    $str_post_url = $_POST['url'];
    $str_post_ttl = $_POST['ttl'];
    $str_offer = '';
    $str_failhint = '';

    // Validate POST inputs
    if (is_int($str_post_ttl)):
        if (($int_ttl_min <= $str_post_ttl) && ($str_post_ttl <= $int_ttl_max)):
            // success
        else:
            $str_failhint = 'bad ttl';
        endif;
    else:
        $str_failhint = 'bad ttl';
    endif;

    $arr_urlpart = [];
    if (filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED |
                FILTER_FLAG_HOST_REQUIRED | FILTER_FLAG_PATH_REQUIRED)): // TODO: URL with UTF-8 fails
        $arr_urlpart = parse_url($url);
        if (in_array($arr_urlpart['scheme'], $arr_schemeavil) &&
                strpos($arr_urlpart['host'], '.')): // host without dot is invalid domain name
            // success
        else:
            $str_failhint = 'bad url';
        endif;
    else:
        $str_failhint = 'bad url';
    endif;



// echo(json_encode($arr_returnjson));

endif; // dont act on dummy requests
?>
