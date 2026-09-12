<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/dumpers/functions.php';
require_once __DIR__ . '/ReconstructUrlTests.php';

final class DumpersTest extends TestCase
{
    use ReconstructUrlTests;

    // 14 bytes: dst MAC, src MAC, EtherType
    private const ETH_IPV4 = 'ffffffffffff001122334455' . '0800';
    private const ETH_IPV6 = 'ffffffffffff001122334455' . '86DD';

    public function testHasAddressesDetectsEightCharOffsetPrefix(): void
    {
        $this->assertTrue(hasAddresses("00000000 ff ff ff ff"));
        $this->assertFalse(hasAddresses("ff ff ff ff"));
        $this->assertFalse(hasAddresses(""));
    }

    public function testStripAddressesRemovesOffsetPrefixOnEveryLineAndAllWhitespace(): void
    {
        $dump = "00000000 ff ff ff ff\n00000004 00 11 22 33";
        $this->assertSame("ffffffff00112233", stripAddresses($dump));
    }

    public function testStripAddressesHandlesCrlf(): void
    {
        $dump = "00000000 ff ff\r\n00000002 00 11";
        $this->assertSame("ffff0011", stripAddresses($dump));
    }

    public function testStripAddressesWithoutPrefixOnlyRemovesWhitespace(): void
    {
        $this->assertSame("ffff0011", stripAddresses("ff ff\n00 11"));
    }

    public function testIsEthernetDetectsIpv4AndIpv6EtherTypes(): void
    {
        $this->assertTrue(isEthernet(self::ETH_IPV4));
        $this->assertTrue(isEthernet(self::ETH_IPV6));
    }

    public function testIsEthernetRejectsShortOrOtherEtherType(): void
    {
        $this->assertFalse(isEthernet("ffff"));
        $this->assertFalse(isEthernet('ffffffffffff001122334455' . '1234'));
    }

    public function testIsEthernetDoesNotPrintAnything(): void
    {
        $this->expectOutputString('');
        isEthernet('ffffffffffff001122334455' . '1234');
        isEthernet(self::ETH_IPV4);
    }

    public function testPrintAsciiBytePrintableAndNonPrintable(): void
    {
        $this->expectOutputString('A&bull;&bull;');
        printAsciiByte('41');
        printAsciiByte('00');
        printAsciiByte('7f');
    }

    public function testPrintHexByteLeftPadsSingleDigit(): void
    {
        ob_start();
        printHexByte('a', false, false, 'x');
        $this->assertStringContainsString('>0a&nbsp;<', ob_get_clean());
    }

    public function testPrintHexByteFlagsNonHexInput(): void
    {
        ob_start();
        printHexByte('zz', false, false, 'x');
        $this->assertStringContainsString('color="red"', ob_get_clean());
    }
}
