{{-- 
    TABLE D'INFORMATIONS PDF
    Usage: @include('pdf.partials.info-table', ['items' => [...]])
    
    Chaque item: ['label' => 'Label', 'value' => 'Valeur']
--}}
@if(isset($items) && count($items) > 0)
<table style="width: 100%; margin: 10px 0;">
    @foreach($items as $item)
        @if(isset($item['value']) && $item['value'] !== null && $item['value'] !== '')
        <tr>
            <td style="width: 35%; background: #f9fafb; font-weight: bold; border: 1px solid #e5e7eb; padding: 6px 10px;">
                {{ $item['label'] }}
            </td>
            <td style="border: 1px solid #e5e7eb; padding: 6px 10px;">
                @if(isset($item['badge']))
                    <span class="badge badge-{{ $item['badge'] }}">{{ $item['value'] }}</span>
                @else
                    {{ $item['value'] }}
                @endif
            </td>
        </tr>
        @endif
    @endforeach
</table>
@endif












