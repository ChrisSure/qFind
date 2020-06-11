<?php

namespace App\Service\Pagination;

use Illuminate\Http\Request;

class PaginationService
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function build(int $totalRecords)
    {
        $uriString = $this->prepareUriString();
        $page = $this->getPage();
        $totalPages = ceil($totalRecords / $this->getLimitPage());

        return [
            'url' => $uriString,
            'page' => $page,
            'totalPages' => $totalPages
        ];
    }

    public function prepareUriString(): string
    {
        $query = $this->removePage($this->request->getQueryString());
        $symbol = ($query) ? '&' : '';
        return $this->request->url() . '?' . $query . $symbol;
    }

    public function getPage(): int
    {
        $query = explode('&', $this->request->getQueryString());
        foreach ($query as $value) {
            if (substr($value, 0, 4) === 'page') {
                return (int)(explode('=', $value)[1]);
            }
        }
        return 1;
    }

    public function removePage(string $query = null): string
    {
        $query = explode('&', $query);
        foreach ($query as $key => $value) {
            if (substr($value, 0, 4) === 'page') {
                unset($query[$key]);
            }
        }
        return implode('&', $query);
    }

    public function getLimitPage(): int
    {
        return env('PAGE_COUNT', 20);
    }
}
