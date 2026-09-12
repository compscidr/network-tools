<?php
use PHPUnit\Framework\TestCase;

/** Renders the shared stats page end-to-end against a throwaway sqlite db. */
final class StatsPageTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        define('PING_VERSION', 4);
        define('PING_DB', tempnam(sys_get_temp_dir(), 'ping') . '.db');
        define('GA_ID', 'G-TEST');
        require_once __DIR__ . '/../src/common/ping.php';
        require_once __DIR__ . '/../src/common/ping-template.php';
        ob_start();
        require __DIR__ . '/../src/common/ping-migrate.php';
        ob_end_clean();
    }

    private function render(string $host): string
    {
        $_SERVER = ['SCRIPT_NAME' => '/stats.php', 'HTTP_HOST' => 'www.x.test', 'REQUEST_URI' => '/stats.php', 'HTTPS' => 'on'] + $_SERVER;
        $_GET = ['host' => $host];
        ob_start();
        require __DIR__ . '/../src/common/ping-stats.php';
        return ob_get_clean();
    }

    public function testHostFromQueryIsEscapedInOutput(): void
    {
        $html = $this->render('<script>alert(1)</script>');
        $this->assertStringNotContainsString('<script>alert', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }

    public function testHostIsUrlEncodedInPingLink(): void
    {
        $html = $this->render('a&b');
        $this->assertStringContainsString('?host=a%26b"', $html);
    }
}
