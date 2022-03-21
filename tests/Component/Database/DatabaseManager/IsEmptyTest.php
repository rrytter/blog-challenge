<?php

namespace App\Tests\Component\Database\DatabaseManager;

use App\Component\Database\DatabaseManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class IsEmptyTest extends TestCase
{
    private DatabaseManager|MockObject $sut;
    
    public function __construct()
    {
        parent::__construct();
        $this->sut = $this->getMockBuilder(DatabaseManager::class)
            ->setConstructorArgs([[]])
            ->onlyMethods(['getParameters'])
            ->getMock();
    }

    /**
     * @test
     */
    public function itWillReturnFalse(): void
    {
        $this->assertFalse($this->sut->isEmpty());
    }

     /**
     * @test
     */
    public function itWillFoo(): void
    {
        $this->sut->expects($this->once())->method('getParameters')->willReturn([]);
        $this->assertFalse($this->sut->isEmpty());
    }
}