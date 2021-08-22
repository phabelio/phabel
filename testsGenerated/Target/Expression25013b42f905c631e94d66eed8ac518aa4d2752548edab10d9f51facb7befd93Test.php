<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;
/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Expression25013b42f905c631e94d66eed8ac518aa4d2752548edab10d9f51facb7befd93Test extends TestCase
{
    function test01056872858ca5b0e3ae7917950a18040b1be408b5f362f51c2ef149e433ce7b()
    {
        $this->assertTrue(true);
        fn() => "{throw $test}";
    }
    function test01359c3a4d936cebb0b975df9f7d627392e3cf423242d47d4b06da13d529536f()
    {
        $this->assertTrue(true);
        fn() => (string) ($test >> $test);
    }
    function test03b7c07c2e1edeb2fdd5242380992eca3e057a86580b3096ebee45eb4a0d3691()
    {
        $this->assertTrue(true);
        fn() => "{0}";
    }
    function test043481fdae7575529076085ff21022c913f08b487f55f67466f347b76ce07b90()
    {
        $this->assertTrue(true);
        fn() => "{ $test}";
    }
    function test04c7c1581e4e0f9393fc8cd7aa8f191d54323d23b57958950e8cae4a06ff2357()
    {
        $this->assertTrue(true);
        fn() => (string) (int) $test;
    }
    function test08009bbc9ba5170ba333bf82e216cfecb28f91b9ac150d646a55ca312697a9cc()
    {
        $this->assertTrue(true);
        fn() => (string) (object) $test;
    }
    function test08b8b4078888ef485e7507d99adca6f8b62e90dfdcd0ff27f7cbb5643bf55880()
    {
        $this->assertTrue(true);
        fn() => (string) ($test and $test);
    }
    function test0ae5ae2159b6de55cbe44be7aa9bb564f5a44c70ce8a596462bf41b24c9d5870()
    {
        $this->assertTrue(true);
        fn() => (string) ($test <= $test);
    }
    function test0d89c86dfa31c98e56f0c8d1a0b70caae898521dcd88bb752ced6ce94f2cdab6()
    {
        $this->assertTrue(true);
        fn() => (string) __CLASS__;
    }
    function test1108b209e5747068f91296593c3f5c5b0c5dad63dc227e91fda535f7caba50e4()
    {
        $this->assertTrue(true);
        fn() => "{(bool) $test}";
    }
    function test17f3c86ee576b2944e902c9e51d123e0f08f03664b43f5e976f4b473731e4410()
    {
        $this->assertTrue(true);
        fn() => "{$test::${$test}}";
    }
    function test190a63516b7674d26cad9f10552c9e3dce31ad599656d4b566b898e64d7a55ac()
    {
        $this->assertTrue(true);
        fn() => "{function () {
        }}";
    }
    function test1df85260b2acb5e316a7465d52587e52eee2763ec694e35d3be1e007713f88d7()
    {
        $this->assertTrue(true);
        fn() => "{$test()}";
    }
    function test1eefb45c98444a6a6f25bfbc8cdf60c424ce5a597b31162ddb6b77da7d448fa0()
    {
        $this->assertTrue(true);
        fn() => "{clone $test}";
    }
    function test1efcf2c67e8b26a68632a0419241d59f300f63d7d5939eb876f6c841daf46a12()
    {
        $this->assertTrue(true);
        fn() => (string) __METHOD__;
    }
    function test25c3afc650bf2dde722edce71842ee2f8baa292193b9f592d90b7f936d79104c()
    {
        $this->assertTrue(true);
        fn() => (string) __FILE__;
    }
    function test2d8efc7b0e09d56870114876756619e092d12c885ebb356be11d24e66975a1fc()
    {
        $this->assertTrue(true);
        fn() => "{__NAMESPACE__}";
    }
    function test304cc764ed1d647bd528786a0c6b567a56789b9ff799d7d36a67aa4c693d3f04()
    {
        $this->assertTrue(true);
        fn() => "{--$test}";
    }
    function test3057343dee0ea4b22b8733f662858e7b40ef96941f7c384f443cf8eeec374bc1()
    {
        $this->assertTrue(true);
        fn() => "{__LINE__}";
    }
    function test387a3115c5482ed8f5fd8b07d6a7929a8b6137cfdc39f3c43233224d32333de3()
    {
        $this->assertTrue(true);
        fn() => (string) ($test << $test);
    }
    function test395331659857cc4db83f68123f7ae6ef394ed019be78d527654957605eaff7e5()
    {
        $this->assertTrue(true);
        fn() => "{__FUNCTION__}";
    }
    function test3b59fd81374e63ec559c7783b56014fb492e080d876995bd3096f8fa47bcc46a()
    {
        $this->assertTrue(true);
        fn() => "{0.0}";
    }
    function test3b69e37b49cf15089b4692f42a456bd46fd5277574f0d4b7a579f7e2bb614efa()
    {
        $this->assertTrue(true);
        fn() => "{+$test}";
    }
    function test3b98dd95f42a327896c34ea63e68363f34052d479affe837a36147d46346a324()
    {
        $this->assertTrue(true);
        fn() => "{@$test}";
    }
    function test3f9ed031b4350e5ad689ed9bfb2bc0c90a8659486afffbc50a11d27a7228a050()
    {
        $this->assertTrue(true);
        fn() => (string) ($test + $test);
    }
    function test4ce4fd14adb8c8464337d4ce0466ffe706506b396fac3a6efc8e64e92210ec38()
    {
        $this->assertTrue(true);
        fn() => "{self}";
    }
    function test4ee739ee8974a6ef0064f18d3592d9f57deefdced8004ebd1b463528df19dca4()
    {
        $this->assertTrue(true);
        fn() => "{(yield $test => $test)}";
    }
    function test50d3e636436ebb45f4b39454a88cf76cd4306e2a0b477d0dda22e6b88c806522()
    {
        $this->assertTrue(true);
        fn() => (string) 0.0;
    }
    function test5518dd53f66cce30f44d816139f3eebb228ee25db8eeba08790e3d5a80e0b607()
    {
        $this->assertTrue(true);
        fn() => "{~$test}";
    }
    function test6ec3de7416becc31694ae3c71bf29d5af967b852c4e9e079b6d4cb6120eb8f88()
    {
        $this->assertTrue(true);
        fn() => "{match ($test) {
        }}";
    }
    function test78c6d41c9427741628c9aa94eaade4c28ce22cc5174a6cd081e1e4d95f02e759()
    {
        $this->assertTrue(true);
        fn() => "{$test?->{$test}}";
    }
    function test79f40a685063e9c92a4470ad03de1cdcde551b7aec6940639c5e7c441630d6d8()
    {
        $this->assertTrue(true);
        fn() => "{-$test}";
    }
    function test7ce9aed24ae62dd702269e44d1a14ad0829b2643becb630c14e6a959570547d4()
    {
        $this->assertTrue(true);
        fn() => (string) (array) $test;
    }
    function test844b468bdbca78b2082b4dc4b9d969f674cf4da7ac992e06bf86e862190b004a()
    {
        $this->assertTrue(true);
        fn() => "{(array) $test}";
    }
    function test89f20dc1d430fe8e67fce058de58a509ebaae70de73217925479c00532789a5c()
    {
        $this->assertTrue(true);
        fn() => "{yield from $test}";
    }
    function test8b6fb7e3ada30265ad26c8248a0ba4b3972cce077e25695414b5fa0b6173ed6e()
    {
        $this->assertTrue(true);
        fn() => (string) __DIR__;
    }
    function test909e4b0e45ec239093d1126af326d1b12735a3d3b466754f9423926ece6bc338()
    {
        $this->assertTrue(true);
        fn() => "{__TRAIT__}";
    }
    function test9115c4a625b450f71f17589b9d0c997242d8d5d3315c95af76e81ea1cebaf344()
    {
        $this->assertTrue(true);
        fn() => "{$test?->{$test}()}";
    }
    function test912155d38047e6701929837293ed96d1201aec1e4cc36cd8e78770216c4f554b()
    {
        $this->assertTrue(true);
        fn() => "{(int) $test}";
    }
    function test91d5e39e14fc15d995714fc727056e49d123942ecc5e6d6b5bb66539f44a542d()
    {
        $this->assertTrue(true);
        fn() => "{$test::$test()}";
    }
    function test91e78cd2c9f4c0618015a251c83f2dd8d97bb6bdc792e93cd2eef8fa13033198()
    {
        $this->assertTrue(true);
        fn() => (string) ($test % $test);
    }
    function test92c3b134d5e9bbefd69b86ad33c2e62e06af19c7b85bb87d5043a39912915b83()
    {
        $this->assertTrue(true);
        fn() => (string) 'test';
    }
    function test9547b04b43abc2db6424782c88d744395971ecfe7d3d179369b861634ebe13f4()
    {
        $this->assertTrue(true);
        fn() => "{$test::$test}";
    }
    function test95ef2316d156c8c079091ad76b39cbec3edb5770ea0fd06b857c96399f5a2c9d()
    {
        $this->assertTrue(true);
        fn() => "{(double) $test}";
    }
    function test9cf756522d1ba0dcfa6c69107399352674cc55fdffdf635dea8a2ec2ff184b63()
    {
        $this->assertTrue(true);
        fn() => (string) __FUNCTION__;
    }
    function testa088391a0c3c2f79b8082adbdfe9a243f4203814531495c168d73c2e489a9b3d()
    {
        $this->assertTrue(true);
        fn() => (string) (string) $test;
    }
    function testa36031d8a1b059f33a87801a07b0e139ece1c3c6a7bc0ff867e9db086fd48502()
    {
        $this->assertTrue(true);
        fn() => "{``}";
    }
    function testa48c880cef04f8419c0572194465502d60deced9f2efb5bc36ee5522fc709040()
    {
        $this->assertTrue(true);
        fn() => (string) (double) $test;
    }
    function testa4f0956a87f7a5216c086742648543e003d3c25303e76ed82af29bf6eabaa0ef()
    {
        $this->assertTrue(true);
        fn() => (string) ($test !== $test);
    }
    function testa563c0f8fb309dfcc20be13adc09d7e27c7d7a8eaeee77c59adf7fb21f73d33b()
    {
        $this->assertTrue(true);
        fn() => (string) "{$test}";
    }
    function testa5ae5acfbf142d825d4ffcccad9ce3aace2b19bae3d1531fdf97d217cec81567()
    {
        $this->assertTrue(true);
        fn() => (string) ($test xor $test);
    }
    function testa63e4aa3f183c1a294996bb80b38a23b1357d6545dfcae07d74615ba554429ce()
    {
        $this->assertTrue(true);
        fn() => (string) (bool) $test;
    }
    function testaecf28ba5985e348cfe9b0fd503280dbfb2f76ff8819ae025ad118f16735d1fb()
    {
        $this->assertTrue(true);
        fn() => (string) ($test or $test);
    }
    function testb00e1cf7780bdc67a910171ca57834ddf22e0185cb2b1e1cc1b0fcbdd7bdc463()
    {
        $this->assertTrue(true);
        fn() => (string) ($test <=> $test);
    }
    function testb3fb7c57cdf5d9bf0a093f87bc7513023249919af42ed32d8e8531d65d55c870()
    {
        $this->assertTrue(true);
        fn() => "{isset($test)}";
    }
    function testb6f4b21c1fa024469bb5f79da02e110f8f9da2331e6d4b90ab592e3ba0f6c618()
    {
        $this->assertTrue(true);
        fn() => (string) ($test - $test);
    }
    function testb96194a3608cf22b92bcc6638771b8083f490980322c8be19253c02c30e40020()
    {
        $this->assertTrue(true);
        fn() => "{${$test}}";
    }
    function testbcc17e623526ced2a42b88741e7a2979bbe8b0233f52d50f866458e57daedea0()
    {
        $this->assertTrue(true);
        fn() => "{[]}";
    }
    function testbe5606b8caf2628d2b2f19f6b240751cea91678b267ff04bc7b650f8dc0b11a3()
    {
        $this->assertTrue(true);
        fn() => "{(string) $test}";
    }
    function testc01eebb389783dfb1dde095e4a5813524afa1a0cd03ec5685afcdd6b39e99b73()
    {
        $this->assertTrue(true);
        fn() => (string) __TRAIT__;
    }
    function testc0dc6420d33a0e60131873a4c2e77680546f067dbd726de77dfea93a9faa4d95()
    {
        $this->assertTrue(true);
        fn() => "{__METHOD__}";
    }
    function testc52652f7a0ed7310857e46262215a423851de6e40494906d02607c89b2a4b965()
    {
        $this->assertTrue(true);
        fn() => "{eval($test)}";
    }
    function testc6529ae13cd4a7b206b325a22c06e7444177b2d069425f44c9a56cb809eec92b()
    {
        $this->assertTrue(true);
        fn() => (string) ($test < $test);
    }
    function testc7cd3d6342e5b34de115c2ee2d309858d6d3b1e4739fcf26646b76f72b449ae8()
    {
        $this->assertTrue(true);
        fn() => "{__FILE__}";
    }
    function testc814f4fe057f8a677fd5b379c59f7667cd4ba9bc9178cabcd4b044265d2eec16()
    {
        $this->assertTrue(true);
        fn() => (string) __NAMESPACE__;
    }
    function testc8d5bc1b6ed26eccfd8d834e304bb916d9054e18a80bd319355f457b00d4e55f()
    {
        $this->assertTrue(true);
        fn() => (string) ($test != $test);
    }
    function testc928a583c48c56150eebe054204f71f6c89e2d00578960bb6aec309ac1500cbc()
    {
        $this->assertTrue(true);
        fn() => (string) __LINE__;
    }
    function testc9d8fdf17978dc92876ea1e699bede328adb07c466c703dee7ad4ae5bafe0b81()
    {
        $this->assertTrue(true);
        fn() => (string) ($test === $test);
    }
    function testcce97aeff9982cf6a78e5cfe02a47886d6c89106ae7a88b1201aad13bf3caaea()
    {
        $this->assertTrue(true);
        fn() => "{!$test}";
    }
    function testcfac6e1e873e7dd97c8d500392dbe17e8bb0cd9b723838e69a260ff4e954cb4b()
    {
        $this->assertTrue(true);
        fn() => "{$test}";
    }
    function testd297540b5a4dcd9679f55b37d1c6a15dba2e9019b837290f0194a975a7a9d059()
    {
        $this->assertTrue(true);
        fn() => "{'test'}";
    }
    function testd41da91f1c4ee4013209494709aa6d909583f3d375fae93c8222dc65a3759880()
    {
        $this->assertTrue(true);
        fn() => (string) 0;
    }
    function testd77ab5e416203cb60b885f14e03fb57139140b9c3965bf2452bd5754e2bcdbd1()
    {
        $this->assertTrue(true);
        fn() => (string) $test ** $test;
    }
    function testdab5f74759caf2d2912629e3b862deff9df00d986c82872481beec8247f7b227()
    {
        $this->assertTrue(true);
        fn() => "{++$test}";
    }
    function teste34ce909fadc6f9869314c693a1002b2ab7457ae1521dfad91db73b5798dea6e()
    {
        $this->assertTrue(true);
        fn() => "{$test->{$test}()}";
    }
    function teste6caafd90a63aebaeb9e4a7d86363f6de5dc0338a7477e699490a499bdb82158()
    {
        $this->assertTrue(true);
        fn() => "{__DIR__}";
    }
    function teste8889a7e67e0a47a1fbc21e24815919f91fb8c4f8aa9bf8b851e8a0c6210ac22()
    {
        $this->assertTrue(true);
        fn() => "{$test->{$test}}";
    }
    function testf0105c016c3b8ce4010c9fe148817fcb3144167f5d81e9d75969e0b1dca70bcc()
    {
        $this->assertTrue(true);
        fn() => "{(object) $test}";
    }
    function testf0677225951f49b30e9c372e0191378fc086a886487f551e5ad1f1bde2374cbf()
    {
        $this->assertTrue(true);
        fn() => "{empty($test)}";
    }
    function testf4094c510a0850a2a0e59aebc3496d19c149f56950ffb7be685f52400595fc7a()
    {
        $this->assertTrue(true);
        fn() => "{$test[$test]}";
    }
    function testf9d6e29d49464b10f27fa26faeec930e93c666f437c8503a16bbc6784e27866f()
    {
        $this->assertTrue(true);
        fn() => "{__CLASS__}";
    }
    function testfbe60f8f77c95a4fa056da7d01d97ca3c05678bd91493569bca9273c29174505()
    {
        $this->assertTrue(true);
        fn() => "{print $test}";
    }
    function testfc19f6b10926857d463b6f61588902c42078574ae802eb185c394fed8b683ff4()
    {
        $this->assertTrue(true);
        fn() => "{new $test()}";
    }
    function testfd6f0fd116683ee08d1a55c4936bbb7b4733e02ea364cc1408cba5e8eabfb1f8()
    {
        $this->assertTrue(true);
        fn() => (string) ($test * $test);
    }
}