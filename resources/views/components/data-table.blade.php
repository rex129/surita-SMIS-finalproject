@props([
    'title',
    'paginator',
    'columns' => 1,
    'addRoute' => null,
    'addLabel' => 'Add',
    'search' => null,
    'empty' => 'No records found.',
])

{{-- Unified data table: toolbar (title, search, filters, add button) + table + footer with pagination --}}
<div class="card datatable">
    <div class="toolbar">
        <h1>{{ $title }}</h1>
        <div class="toolbar-right">
            @if ($search || isset($filters))
                <form method="GET" class="filters">
                    @if ($search)<input type="search" name="q" value="{{ request('q') }}" placeholder="{{ $search }}">@endif
                    {{ $filters ?? '' }}
                    <button class="btn secondary">Search</button>
                    @if (request()->hasAny(['q', 'status']))<a class="btn secondary" href="{{ url()->current() }}">Reset</a>@endif
                </form>
            @endif
            @if ($addRoute)<a class="btn" href="{{ $addRoute }}">+ {{ $addLabel }}</a>@endif
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>{{ $head }}</thead>
            <tbody>
                {{ $slot }}
                @if ($paginator->isEmpty())
                    <tr><td colspan="{{ $columns }}" class="empty">{{ $empty }}</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="table-foot">
        <div class="muted">
            @if ($paginator->total() > 0)
                Showing {{ $paginator->firstItem() }}&ndash;{{ $paginator->lastItem() }} of {{ $paginator->total() }}
            @else
                0 results
            @endif
        </div>
        {{ $paginator->links() }}
    </div>
</div>
