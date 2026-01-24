@props([
    'headers' => [],
    'data' => [],
    'striped' => false,
    'hover' => true,
    'bordered' => true,
    'compact' => false,
    'loading' => false,
    'emptyMessage' => 'لا توجد بيانات للعرض',
    'class' => '',
])

@php
    $baseClasses = 'w-full overflow-x-auto';
    
    $tableClasses = 'min-w-full divide-y';
    
    $headerClasses = 'bg-gray-50';
    
    $bodyClasses = 'bg-white divide-y';
    
    $rowClasses = '';
    
    if ($striped) {
        $bodyClasses .= ' divide-y divide-gray-200';
        $rowClasses = 'even:bg-gray-50';
    }
    
    if ($hover) {
        $rowClasses .= ' hover:bg-gray-50';
    }
    
    if ($bordered) {
        $tableClasses .= ' border border-gray-200';
    }
    
    if ($compact) {
        $tableClasses .= ' text-sm';
    }
    
    $allClasses = implode(' ', array_filter([$baseClasses, $class]));
@endphp

<div class="{{ $allClasses }}">
    <table class="{{ $tableClasses }}">
        <thead class="{{ $headerClasses }}">
            <tr>
                @foreach($headers as $header)
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">
                        {{ $header['text'] }}
                        @if(isset($header['sortable']))
                            <button class="ml-2" onclick="sortTable('{{ $header['key'] }}')">
                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                </svg>
                            </button>
                        @endif
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="{{ $bodyClasses }}">
            @if($loading)
                <tr>
                    <td colspan="{{ count($headers) }}" class="px-6 py-4 text-center">
                        <div class="flex justify-center">
                            <svg class="animate-spin h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </td>
                </tr>
            @elseif(count($data) === 0)
                <tr>
                    <td colspan="{{ count($headers) }}" class="px-6 py-4 text-center text-gray-500">
                        {{ $emptyMessage }}
                    </td>
                </tr>
            @else
                @foreach($data as $row)
                    <tr class="{{ $rowClasses }}">
                        @foreach($headers as $header)
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(isset($header['component']))
                                    <x-dynamic-component 
                                        :component="$header['component']" 
                                        :data="$row[$header['key']] ?? null"
                                        :row="$row"
                                    />
                                @else
                                    <span class="text-sm text-gray-900">
                                        {{ $row[$header['key']] ?? '-' }}
                                    </span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

@if(isset($headers[0]['sortable']))
    <script>
        function sortTable(column) {
            // Implement sorting logic here
            console.log('Sorting by:', column);
        }
    </script>
@endif
