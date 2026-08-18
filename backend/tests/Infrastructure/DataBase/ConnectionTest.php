<?php

namespace Tests\Infrastructure\DataBase;

use App\Infrastructure\DataBase\Connection;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    public function testDeveConectarAoBanco(): void
    {
        $connection = Connection::create();

        $this->assertNotNull($connection);
    }
}

?>
