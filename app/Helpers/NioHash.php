<?php
namespace App\Helpers;

class NioHash
{
	public static function of($data=null, $match=null) {
		if(empty($data)) return false;
        $index  = 4;
        $csrf    = csrf_token();
        $ukey    = substr($csrf, -$index, $index);

        $openSSL = (function_exists('openssl_decrypt') && function_exists('openssl_encrypt')) ? true : false;
        if($openSSL===true) {
            $iv = substr(hash('sha256', $ukey), 0, 16);
            $hash = base64_encode(openssl_encrypt($data, 'aes-128-cbc', $ukey, 0, $iv));

            if(!empty($match)) {
                return ($match==$hash) ? true : false;
            }

            return $hash;
        }

        $encode = base64_encode($data.$ukey);

        if(!empty($match)) {
            return ($match==$encode) ? true : false;
        }

        return $encode;
    }

	public static function toID($data=null) {
        if(empty($data)) return false;
        $index  = 4;
        $csrf   = csrf_token();
        $ukey   = substr($csrf, -$index, $index);

        $openSSL = (function_exists('openssl_decrypt') && function_exists('openssl_encrypt')) ? true : false;
        if($openSSL===true) {
            $iv = substr(hash('sha256', $ukey), 0, 16);
            $hash = openssl_decrypt(base64_decode($data), 'aes-128-cbc', $ukey, 0, $iv);
            return ($hash) ? $hash : false;
        }
        return substr(base64_decode($data), 0, -$index);
	}

    public static function etoken($hash, $sync = null) {
        $tokenx = md5(csrf_token());
        $tokeny = substr(csrf_token(), 12, 4);
        $tokenz = substr(csrf_token(), -8, 4);
        $apcode = str_compact(sys_info('pcode'), '');

        $sysco  = strrev(substr($apcode, 0, 4));
        $syncs  = ($sync) ? cipher($sync) : substr(hash('md5', $sysco), 4, 8);

        return substr($tokenx, 4, 6).substr($syncs, 1, 6).substr($apcode, -4).str_dv2($hash, 1).$tokeny.str_dv2($hash, 2).$tokenz.cipher_id($sysco);
    }
}
