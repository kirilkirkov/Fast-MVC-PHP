<?php

namespace Tests;

use Core\QueryBuilder\QueryBuilder;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    public function testSelectWithAllParts(): void
    {
        $query = QueryBuilder::select('id', 'email')
            ->from('users', 'u')
            ->where('u.id = :id', 'u.is_confirmed = 1')
            ->orderBy('u.id DESC')
            ->limit(10)
            ->offset(20);

        $this->assertSame(
            'SELECT id, email FROM users AS u WHERE u.id = :id AND u.is_confirmed = 1 ORDER BY u.id DESC LIMIT 10 OFFSET 20',
            (string) $query
        );
    }

    public function testJoinsKeepOrderAndDoNotOverwriteEachOther(): void
    {
        $query = QueryBuilder::select('*')
            ->from('users', 'u')
            ->innerJoin('orders o ON o.user_id = u.id')
            ->leftJoin('coupons c ON c.id = o.coupon_id')
            ->innerJoin('products p ON p.id = o.product_id');

        $this->assertSame(
            'SELECT * FROM users AS u'
            . ' INNER JOIN orders o ON o.user_id = u.id'
            . ' LEFT JOIN coupons c ON c.id = o.coupon_id'
            . ' INNER JOIN products p ON p.id = o.product_id',
            (string) $query
        );
    }

    public function testInsert(): void
    {
        $query = QueryBuilder::insert('users')->columns('email', 'slug');

        $this->assertSame('INSERT INTO users (email, slug) VALUES (:email, :slug)', (string) $query);
    }

    public function testUpdate(): void
    {
        $query = QueryBuilder::update('users')->set('email', 'slug')->where('id = :id');

        $this->assertSame('UPDATE users SET email = :email, slug = :slug WHERE id = :id', (string) $query);
    }

    public function testDelete(): void
    {
        $query = QueryBuilder::delete('users')->where('id = :id');

        $this->assertSame('DELETE FROM users WHERE id = :id', (string) $query);
    }
}
