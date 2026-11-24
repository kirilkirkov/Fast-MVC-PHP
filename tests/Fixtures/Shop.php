<?php

namespace App\Controllers\Fixtures;

/**
 * Controller used by RoutingTest.
 * The name ends with "p" on purpose - rtrim($class, '.php') used to cut it to "Sho".
 */
class Shop
{
    public function index(): void
    {
        echo 'shop index';
    }

    public function show(string $slug, string $id): void
    {
        echo "product {$slug} #{$id}";
    }
}
