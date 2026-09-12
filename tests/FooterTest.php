<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/common/footer.php';

final class FooterTest extends TestCase
{
    private string $git;

    protected function setUp(): void
    {
        $this->git = sys_get_temp_dir() . '/gitdir-' . uniqid();
        mkdir("{$this->git}/refs/heads", 0777, true);
        file_put_contents("{$this->git}/HEAD", "ref: refs/heads/main\n");
    }

    public function testReadsLooseRef(): void
    {
        file_put_contents("{$this->git}/refs/heads/main", "0123456789abcdef0123456789abcdef01234567\n");
        $this->assertSame('0123456789abcdef0123456789abcdef01234567', gitCommit($this->git));
    }

    public function testReadsPackedRef(): void
    {
        file_put_contents("{$this->git}/packed-refs", "# pack-refs with: peeled fully-peeled sorted\n"
            . "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa refs/heads/other\n"
            . "fedcba9876543210fedcba9876543210fedcba98 refs/heads/main\n");
        $this->assertSame('fedcba9876543210fedcba9876543210fedcba98', gitCommit($this->git));
    }

    public function testDetachedHead(): void
    {
        file_put_contents("{$this->git}/HEAD", "abcdef0123456789abcdef0123456789abcdef01\n");
        $this->assertSame('abcdef0123456789abcdef0123456789abcdef01', gitCommit($this->git));
    }

    public function testMissingGitDirIsEmpty(): void
    {
        $this->assertSame('', gitCommit('/nonexistent'));
    }

    public function testFooterLinksCommitWhenKnown(): void
    {
        file_put_contents("{$this->git}/refs/heads/main", "0123456789abcdef0123456789abcdef01234567\n");
        ob_start(); showFooter($this->git); $html = ob_get_clean();
        $this->assertStringContainsString('href="https://github.com/compscidr/network-tools/commit/0123456789abcdef0123456789abcdef01234567">0123456</a>', $html);
        $this->assertStringContainsString('href="https://www.ping6.network"', $html);
    }

    public function testFooterOmitsCommitWhenUnknown(): void
    {
        ob_start(); showFooter('/nonexistent'); $html = ob_get_clean();
        $this->assertStringNotContainsString('/commit/', $html);
        $this->assertStringContainsString('github.com/compscidr/network-tools"', $html);
    }
}
