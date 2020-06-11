<?php

namespace Tests\Unit\Service\Pagination;

use App\Service\Pagination\PaginationService;
use Illuminate\Http\Request;
use Mockery;
use PHPUnit\Framework\TestCase;

class PaginationServiceTest extends TestCase
{
    /**
     * @test
     */
    public function getPage()
    {
        $requestMock = Mockery::mock(Request::class);
        $requestMock->shouldReceive('getQueryString')->andReturn('page=' . $page = 2);
        $paginationService = new PaginationService($requestMock);
        $result = $paginationService->getPage();

        $this->assertEquals($result, $page);
    }

    /**
     * @test
     */
    public function prepareString()
    {
        $requestMock = Mockery::mock(Request::class);
        $requestMock->shouldReceive('getQueryString')->andReturn('status=new&page=2');
        $requestMock->shouldReceive('url')->andReturn($url = 'http://localhost/index');

        $paginationService = new PaginationService($requestMock);
        $result = $paginationService->prepareUriString();

        $this->assertEquals($result, $url  . '?status=new&');
    }

    /**
     * @test
     */
    public function build()
    {
        $prepareString = 'http://localhost/index?status=new&';
        $requestMock = Mockery::mock(Request::class);
        $requestMock->shouldReceive('getQueryString')->andReturn('status=new&page=' . $page = 2);
        $requestMock->shouldReceive('url')->andReturn($url = 'http://localhost/index');

        $paginationService = new PaginationService($requestMock);
        $result = $paginationService->build($totalRecords = 60);

        $totalPages = ceil($totalRecords / 20);

        $this->assertEquals($result['url'], $prepareString);
        $this->assertEquals($result['page'], $page);
        $this->assertEquals($result['totalPages'], $totalPages);
    }
}
