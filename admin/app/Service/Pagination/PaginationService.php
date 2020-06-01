<?php

namespace App\Service\Pagination;

use Illuminate\Http\Request;

class PaginationService
{
    private $request;

    private $limitPages;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->limitPages = env('PAGE_COUNT', null);
    }

    public function build(int $totalRecords)
    {
        $uriString = $this->prepareUriString();
        $page = $this->getPage();
        $totalPages =  ceil($totalRecords / $this->limitPages);

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
                return  (int)(explode('=', $value)[1]);
            }
        }
        return 1;
    }

    private function removePage(string $query = null): string
    {
        $query = explode('&', $query);
        foreach ($query as $key => $value) {
            if (substr($value, 0, 4) === 'page') {
                unset($query[$key]);
            }
        }
        return implode('&', $query);
    }
}
