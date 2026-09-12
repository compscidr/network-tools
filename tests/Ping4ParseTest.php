<?php
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

require_once __DIR__ . '/PingParseTestBase.php';

#[RunTestsInSeparateProcesses]
final class Ping4ParseTest extends PingParseTestBase
{
    protected function site(): string { return 'ping4'; }
}
