<?php

namespace App\Traits;

trait PaginationTrait
{
    public function metaData(): array
    {
        $options = $this->getOptions();
        $pageName = $options['pageName'];
        $path = $options['path'];
        $lastPage = $this->lastPage();

        return [
            'total' => $this->total(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'last_page' => $lastPage,
            'first_page_url' => $path.'?'.$pageName.'=1',
            'last_page_url' => $path.'?'.$pageName.'='.$lastPage,
            'next_page_url' => $this->nextPageUrl(),
            'prev_page_url' => $this->previousPageUrl(),
            'path' => $this->path(),
            'from' => $this->firstItem(),
            'to' => $this->lastItem(),
        ];
    }
}
