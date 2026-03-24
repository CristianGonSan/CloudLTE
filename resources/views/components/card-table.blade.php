@props([
    'tableClass' => 'table table-hover table-striped m-0',
    'pagination' => null,
])

<div class="card">
    @if (isset($header))
        <div class="card-header border-0 pb-2">
            {{ $header }}
        </div>
    @endif

    <div class="card-body table-responsive p-0">
        <table class="{{ $tableClass }}">
            {{ $slot }}
        </table>
    </div>

    @if (isset($footer))
        <div class="card-footer border-0 pb-0">
            {{ $footer }}
        </div>
    @else
        @if ($pagination?->hasPages())
            <div class="card-footer border-0 pb-0">
                {{ $pagination->links() }}
            </div>
        @endif
    @endif
</div>
