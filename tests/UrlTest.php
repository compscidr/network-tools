<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/common/url.php';
require_once __DIR__ . '/ReconstructUrlTests.php';

final class UrlTest extends TestCase
{
    use ReconstructUrlTests;
}
