<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/ReconstructUrlTests.php';

/**
 * ping4 and ping6 carry identical parsing code but clash on function/class
 * names, so each concrete subclass loads one site in its own process.
 */
abstract class PingParseTestBase extends TestCase
{
    use ReconstructUrlTests;

    abstract protected function site(): string;

    protected function setUp(): void
    {
        require_once __DIR__ . '/../src/' . $this->site() . '/functions.php';
    }

    private const OK = "PING 8.8.8.8 (8.8.8.8) 56(84) bytes of data.\n"
        . "64 bytes from 8.8.8.8: icmp_seq=1 ttl=116 time=18.4 ms\n"
        . "64 bytes from 8.8.8.8: icmp_seq=2 ttl=116 time=12.1 ms\n"
        . "64 bytes from 8.8.8.8: icmp_seq=3 ttl=116 time=15.5 ms\n"
        . "\n--- 8.8.8.8 ping statistics ---\n"
        . "3 packets transmitted, 3 received, 0% packet loss, time 2002ms\n"
        . "rtt min/avg/max/mdev = 12.053/15.322/18.384/2.588 ms\n";

    private const LOSS = "PING 192.0.2.1 (192.0.2.1) 56(84) bytes of data.\n"
        . "\n--- 192.0.2.1 ping statistics ---\n"
        . "2 packets transmitted, 0 received, 100% packet loss, time 1055ms\n\n";

    public function testSuccessfulPingYieldsIpAndAverageRtt(): void
    {
        $r = parsePingOutput('dns.google', '8.8.8.8', self::OK);
        $this->assertSame('8.8.8.8', $r->ip);
        $this->assertSame('dns.google', $r->hostname);
        $this->assertSame(15.322, $r->avg_rtt_ms);
        $this->assertFalse($r->down);
        $this->assertTrue($r->valid);
        $this->assertSame(self::OK, $r->rawResult);
    }

    public function testTotalPacketLossIsDownButValid(): void
    {
        $r = parsePingOutput('192.0.2.1', '192.0.2.1', self::LOSS);
        $this->assertSame('192.0.2.1', $r->ip);
        $this->assertTrue($r->down);
        $this->assertTrue($r->valid);
        $this->assertSame(0.0, $r->avg_rtt_ms);
    }

    public function testEmptyOutputIsUnreachable(): void
    {
        $r = parsePingOutput('example.test', '192.0.2.1', '');
        $this->assertTrue($r->down);
        $this->assertTrue($r->valid);
        $this->assertSame('192.0.2.1', $r->hostname);
        $this->assertSame('example.test is Unreachable', $r->rawResult);
    }

    public function testHostnameIsHtmlEscaped(): void
    {
        $r = parsePingOutput('<b>x</b>', '8.8.8.8', self::OK);
        $this->assertSame('&lt;b&gt;x&lt;/b&gt;', $r->hostname);
    }
}
