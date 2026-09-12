<?php
/** Shared by every site's copy of reconstruct_url(). */
trait ReconstructUrlTests
{
    private function server(string $uri, bool $https = true): void
    {
        $_SERVER['HTTPS'] = $https ? 'on' : 'off';
        $_SERVER['HTTP_HOST'] = 'www.example.test';
        $_SERVER['SERVER_PORT'] = $https ? '443' : '80';
        $_SERVER['REQUEST_URI'] = $uri;
    }

    public function testReconstructUrlRootStaysRoot(): void
    {
        $this->server('/');
        $this->assertSame('https://www.example.test/', reconstruct_url());
    }

    public function testReconstructUrlStripsScriptAndQuery(): void
    {
        $this->server('/index.php?host=8.8.8.8');
        $this->assertSame('https://www.example.test/', reconstruct_url());
        $this->server('/stats.php?host=8.8.8.8');
        $this->assertSame('https://www.example.test/', reconstruct_url());
    }

    public function testReconstructUrlHonoursForwardedProtoBehindProxy(): void
    {
        $this->server('/index.php?host=x', https: false);
        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
        $this->assertSame('https://www.example.test/', reconstruct_url());
        unset($_SERVER['HTTP_X_FORWARDED_PROTO']);
    }

    public function testReconstructUrlUsesHttpWhenNotHttps(): void
    {
        $this->server('/', https: false);
        $this->assertSame('http://www.example.test/', reconstruct_url());
    }
}
