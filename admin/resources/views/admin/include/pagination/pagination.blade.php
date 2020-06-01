<nav aria-label="Page navigation example">
    <ul class="pagination">
        @if($page > 1 )
            <li class="page-item"><a class="page-link" href="{{$url}}page={{ $page - 1 }}">Previous</a></li>
        @endif

        @if($page < $totalPages )
            <li class="page-item"><a class="page-link" href="{{$url}}page={{ $page + 1 }}">Next</a></li>
        @endif
    </ul>
</nav>
