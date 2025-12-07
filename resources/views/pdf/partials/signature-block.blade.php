{{-- 
    BLOC DE SIGNATURES PDF
    Usage: @include('pdf.partials.signature-block', [
        'left' => ['title' => 'Pour la CEEAC', 'name' => 'Nom', 'position' => 'Fonction'],
        'right' => ['title' => 'Pour le Pays hôte', 'name' => 'Nom', 'position' => 'Fonction']
    ])
--}}
<div class="signature-section">
    <h3 style="margin-bottom: 20px;">Signatures</h3>
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 45%; border: none; vertical-align: top; text-align: center;">
                <div style="margin-bottom: 10px;">
                    <strong>{{ $left['title'] ?? 'Pour la CEEAC' }}</strong>
                </div>
                @if(isset($left['name']) && $left['name'])
                    <div style="margin-top: 10px;">{{ $left['name'] }}</div>
                    @if(isset($left['position']) && $left['position'])
                        <div style="font-size: 10px; color: #6b7280;">{{ $left['position'] }}</div>
                    @endif
                @endif
                <div style="border-top: 2px solid #374151; margin-top: 50px; padding-top: 8px;">
                    Signature et cachet
                </div>
            </td>
            <td style="width: 10%; border: none;"></td>
            <td style="width: 45%; border: none; vertical-align: top; text-align: center;">
                <div style="margin-bottom: 10px;">
                    <strong>{{ $right['title'] ?? 'Pour le Pays hôte' }}</strong>
                </div>
                @if(isset($right['name']) && $right['name'])
                    <div style="margin-top: 10px;">{{ $right['name'] }}</div>
                    @if(isset($right['position']) && $right['position'])
                        <div style="font-size: 10px; color: #6b7280;">{{ $right['position'] }}</div>
                    @endif
                @endif
                <div style="border-top: 2px solid #374151; margin-top: 50px; padding-top: 8px;">
                    Signature et cachet
                </div>
            </td>
        </tr>
    </table>
</div>

