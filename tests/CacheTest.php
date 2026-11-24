<?php

namespace Tests;

use Core\Cache;
use PHPUnit\Framework\TestCase;

/**
 * Uses its own temp dir, so the result doesn't depend on cache settings in env.php
 */
class CacheTest extends TestCase
{
    private string $cacheDir;

    protected function setUp(): void
    {
        $this->cacheDir = sys_get_temp_dir() . '/fast-mvc-cache-test-' . uniqid() . '/';
        mkdir($this->cacheDir);
    }

    protected function tearDown(): void
    {
        array_map('unlink', glob($this->cacheDir . '*'));
        rmdir($this->cacheDir);
    }

    public function testItStoresAndReadsArray(): void
    {
        $data = ['id' => 1, 'email' => "it's \"quoted\"", 'tags' => ['a', 'b']];

        $this->assertTrue(Cache::set('key', $data, $this->cacheDir));
        $this->assertSame($data, Cache::get('key', $this->cacheDir));
    }

    public function testItReturnsFalseForMissingKey(): void
    {
        $this->assertFalse(Cache::get('missing', $this->cacheDir));
    }

    public function testItReturnsCachedEmptyArray(): void
    {
        Cache::set('empty', [], $this->cacheDir);

        $this->assertSame([], Cache::get('empty', $this->cacheDir));
    }

    public function testItDoesNotStoreNonArrayValues(): void
    {
        $this->assertFalse(Cache::set('row', false, $this->cacheDir));
        $this->assertFalse(Cache::get('row', $this->cacheDir));
    }

    public function testItOverwritesValueAndLeavesNoTempFiles(): void
    {
        Cache::set('key', ['old'], $this->cacheDir);
        Cache::set('key', ['new'], $this->cacheDir);

        $this->assertSame(['new'], Cache::get('key', $this->cacheDir));
        $this->assertSame([$this->cacheDir . 'key'], glob($this->cacheDir . '*'));
    }
}
