<?php

use Dcrypt\Mcrypt;

class McryptTest extends \TestSupport
{

    public function testEngine()
    {
        // If mcrypt not supported, escape here
        if (self::mcryptDeprecated()) {
            $this->assertTrue(true);
            return;
        }

        $modes = \TestSupport::mcryptModes();
        $ciphers = \TestSupport::mcryptCiphers();

        $inp = 'aaaaaaaaaaaaaaa';
        $key = 'AAAAAAAAAAAAAAA';

        $output = array(
            'key' => $key,
            'inp' => $inp,
        );

        foreach (hash_algos() as $algo) {

            $output[$algo] = array();
            $cost = 0;

            foreach ($modes as $mode) {
                $output[$algo][$mode] = array();

                foreach ($ciphers as $cipher) {
                    $encrypted = Mcrypt::encrypt($inp, $key, $cost, $cipher, $mode, $algo);

                    $output[$algo][$mode][$cipher] = base64_encode($encrypted);

                    $this->assertEquals($inp, Mcrypt::decrypt($encrypted, $key, $cost, $cipher, $mode, $algo));
                }
            }
        }

        #file_put_contents(__DIR__ . '/mcryptvectors.json', json_encode($output, JSON_PRETTY_PRINT));
    }

    public function testKnownVectors()
    {
        // If mcrypt not supported, escape here
        if (self::mcryptDeprecated()) {
            $this->assertTrue(true);
            return;
        }        
        
        // This does not work on php 5.3 for some reason
        // @todo: investigate
        if (version_compare(PHP_VERSION, '5.4.0') < 0) {
            $this->assertTrue(true);
            return;
        }

        $json = json_decode(file_get_contents(__DIR__ . '/mcryptvectors.json'), true);

        foreach ($json as $algo => $r1) {
            if (is_array($r1) && in_array($algo, hash_algos())) {
                foreach ($r1 as $mode => $r2) {
                    foreach ($r2 as $cipher => $r3) {
                        if (in_array($cipher, mcrypt_list_algorithms())) {
                            try {
                                $this->assertEquals($json['inp'], Mcrypt::decrypt(base64_decode($r3), $json['key'], 0, $cipher, $mode, $algo));
                            } catch (\exception $e) {
                                echo "===$algo===$mode===$cipher";
                                throw $e;
                            }
                        }
                    }
                }
            }
        }
    }
}
