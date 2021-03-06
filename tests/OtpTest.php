<?php

use Dcrypt\Otp;

class OtpTest extends \PHPUnit\Framework\TestCase
{
    public function testCompatibility()
    {
        $h = hex2bin('cfceb8891c58071deedbd1');
        $k = 'key123';
        $p = 'hello world';

        $this->assertEquals($p, Otp::crypt($h, $k));
    }

    public function testCrypt()
    {
        foreach (array(1, 1000) as $mult) {
            $input = str_repeat('A', 4 * $mult);
            $key = openssl_random_pseudo_bytes(32);

            /*
             * Test encryption
             */
            $encrypted = Otp::crypt($input, $key);
            $this->assertEquals(strlen($input), strlen($encrypted));
            $this->assertNotEquals($input, $encrypted);

            /*
             * Test decryption
             */
            $decrypted = Otp::crypt($encrypted, $key);
            $this->assertEquals($input, $decrypted);
        }
    }

    public function testVector()
    {
        $input = 'hello world';
        $pass = 'password';
        $vector = base64_decode('vusXragCy83KQFo');

        $this->assertEquals($input, Otp::crypt($vector, $pass));
    }
}
