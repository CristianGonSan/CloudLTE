<thead>
    <tr class="text-nowrap">
        @foreach ($theadConfig as $col)
            @php
                $column = $col['column'] ?? null;
                $label = $col['label'];
                $align = $col['align'] ?? 'left';
                $style = $col['style'] ?? '';
            @endphp

            <th scope="col" class="text-{{ $align }} {{ $column ? 'cursor-pointer' : '' }}"
                style="{{ $style }}"
                @if ($column) wire:click="toggleSortDirection('{{ $column }}')" @endif>
                {{ $label }}

                @if ($column)
                    <i
                        class="{{ $this->checkColumnSort(
                            $column,
                            'fas fa-fw fa-sort ml-1 text-muted',
                            'fas fa-fw fa-sort-up ml-1',
                            'fas fa-fw fa-sort-down ml-1',
                        ) }}">
                    </i>
                @endif
            </th>
        @endforeach
    </tr>
</thead>
