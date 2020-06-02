<nav aria-label="Page navigation example">
    <ul class="pagination">
        @if($paginationArray['page'] > 1 )
            <li class="page-item"><a class="page-link" href="{{$paginationArray['url']}}page={{ $paginationArray['page'] - 1 }}">Previous</a></li>
        @endif

        @if($paginationArray['page'] < $paginationArray['totalPages'] )
            <li class="page-item"><a class="page-link" href="{{$paginationArray['url']}}page={{ $paginationArray['page'] + 1 }}">Next</a></li>
        @endif
    </ul>
</nav>
