<?php

use Dcrypt\Aes;
use Dcrypt\Mcrypt;

class BlockCiphersGenericTest extends PHPUnit_Framework_TestCase
{

    public function testCrossDecryptFailure()
    {
        $pw = 'password';
        $encrypted = \Dcrypt\Aes::encrypt('hello world', $pw);
        try {
            \Dcrypt\AesCtr::decrypt($encrypted, $pw);
            $this->assertTrue(false);
        } catch (\Exception $ex) {
            $this->assertTrue(true);
        }
        
        try {
            \Dcrypt\Mcrypt::decrypt($encrypted, $pw);
            $this->assertTrue(false);
        } catch (\Exception $ex) {
            $this->assertTrue(true);
        }
        
        $encrypted = \Dcrypt\AesCtr::encrypt('hello world', $pw);        
        try {
            \Dcrypt\Aes::decrypt($encrypted, $pw);
            $this->assertTrue(false);
        } catch (\Exception $ex) {
            $this->assertTrue(true);
        }
    }

}
