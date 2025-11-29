@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex justify-between items-center py-4">
        <div class="flex-1 flex justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="text-gray-500 cursor-not-allowed">Previous</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="text-indigo-600 hover:text-indigo-900">Previous</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="text-indigo-600 hover:text-indigo-900">Next</a>
            @else
                <span class="text-gray-500 cursor-not-allowed">Next</span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:justify-center">
            <ul class="flex pl-0 list-none space-x-1">
                @if ($paginator->onFirstPage())
                    <li><span class="px-3 py-2 bg-gray-300 text-gray-500 cursor-not-allowed">Previous</span></li>
                @else
                    <li><a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 bg-indigo-600 text-white hover:bg-indigo-700">Previous</a></li>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li><span class="px-3 py-2 bg-gray-300 text-gray-500">{{ $element }}</span></li>
                    @elseif (is_array($element))
                        @foreach ($element as $page => $url)
                            <li>
                                @if ($page == $paginator->currentPage())
                                    <span class="px-3 py-2 bg-indigo-600 text-white">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="px-3 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300">{{ $page }}</a>
                                @endif
                            </li>
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <li><a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 bg-indigo-600 text-white hover:bg-indigo-700">Next</a></li>
                @else
                    <li><span class="px-3 py-2 bg-gray-300 text-gray-500 cursor-not-allowed">Next</span></li>
                @endif
            </ul>
        </div>
    </nav>
@endif
