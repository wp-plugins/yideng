<?php

class yiDengAes {
    private $_iv = '';
    private $_secret = '';

    public function __construct($appId,$appSecret){
        $this->_iv = substr($appId.'0000000000000000', 0,16);
        $this->_secret = hash('md5',$appSecret,true);
    }

    public function yiDengDecode($secretData){
        return openssl_decrypt(urldecode($secretData),'aes-128-cbc',$this->_secret,false,$this->_iv);
    }

    public function yiDengEncode($data){
        return urlencode(openssl_encrypt($data,'aes-128-cbc',$this->_secret,false,$this->_iv));
    }
}

?>