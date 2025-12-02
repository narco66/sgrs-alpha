@props(['headers', 'emptyMessage' => 'Aucun élément trouvé.', 'emptyIcon' => 'bi-inbox'])

<div class="modern-table">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    @foreach($headers as $header)
                        <th class="{{ $header['sortable'] ?? false ? 'sortable' : '' }}" 
                            @if(isset($header['onclick'])) onclick="{{ $header['onclick'] }}" @endif>
                            {{ $header['label'] }}
                            @if(isset($header['icon']))
                                <i class="bi {{ $header['icon'] }}"></i>
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                {{ $slot }}
                
                @if(empty($slot->toHtml()) || (isset($empty) && $empty))
                    <tr>
                        <td colspan="{{ count($headers) }}" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi {{ $emptyIcon }} empty-state-icon"></i>
                                <div class="empty-state-title">Aucun résultat</div>
                                <div class="empty-state-text">{{ $emptyMessage }}</div>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
