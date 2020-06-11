<?php

namespace App\Tests\Unit\Service\Auth;

use App\Entity\User\User;
use App\Service\Helper\SerializeService;
use App\Tests\Unit\Base;

class SerializeServiceTest extends Base
{
    /**
     * @test
     */
    public function serialize(): void
    {
        $serializeService = new SerializeService();
        $result = $serializeService->serialize(User::class);

        $this->assertTrue(is_string($result));
    }

    /**
     * @test
     */
    public function deserialize(): void
    {
        $serializeService = new SerializeService();
        $serializeUser = $serializeService->serialize(User::class);
        $result = $serializeService->deserialize($serializeUser, User::class, 'json');

        $this->assertTrue($result instanceof User);
    }
}