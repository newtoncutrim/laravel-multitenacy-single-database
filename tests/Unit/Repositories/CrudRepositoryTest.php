<?php

namespace Tests\Unit\Repositories;

use App\Models\Client;
use App\Repositories\Contracts\CrudRepositoryInterface;
use App\Repositories\Eloquent\CrudRepository;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

class CrudRepositoryTest extends TestCase
{
    public function test_it_accepts_an_eloquent_model_class(): void
    {
        $repository = new CrudRepository(Client::class);

        $this->assertInstanceOf(CrudRepositoryInterface::class, $repository);
    }

    public function test_it_rejects_a_class_that_is_not_an_eloquent_model(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new CrudRepository(stdClass::class);
    }
}
