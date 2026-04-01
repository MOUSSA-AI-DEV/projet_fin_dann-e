@if($references->isEmpty())
    <div style="text-align: center; padding: 4rem 2rem; color: #94a3b8;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">📋</div>
        <p style="font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Aucune référence trouvée</p>
        <p style="font-size: 0.875rem;">Essayez un autre terme de recherche.</p>
    </div>
@else
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #f1f5f9; background: #fafafa;">
                    <th style="padding: 0.85rem 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">ID</th>
                    <th style="padding: 0.85rem 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Code / Série</th>
                    <th style="padding: 0.85rem 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Pièce Associée</th>
                    <th style="padding: 0.85rem 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Voitures Compatibles</th>
                    <th style="padding: 0.85rem 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Stock</th>
                    <th style="padding: 0.85rem 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Prix</th>
                    <th style="padding: 0.85rem 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Statut</th>
                    <th style="padding: 0.85rem 1rem; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($references as $reference)
                    <tr class="ref-row" style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;">
                        <td style="padding: 0.9rem 1rem; font-weight: 600; color: #94a3b8; font-size: 0.8rem;">#{{ $reference->id }}</td>
                        <td style="padding: 0.9rem 1rem; min-width: 150px;">
                            <div style="font-weight: 700; color: #1e293b; font-size: 0.875rem; letter-spacing: 0.3px;">{{ $reference->reference }}</div>
                            <div style="font-size: 0.78rem; color: #64748b; margin-top: 2px;">{{ $reference->nom }}</div>
                            @if($reference->garantie)
                                <div style="margin-top: 4px;"><span class="pill pill-gray">🛡 {{ $reference->garantie }}</span></div>
                            @endif
                        </td>
                        <td style="padding: 0.9rem 1rem; min-width: 160px;">
                            @if($reference->piece)
                                <div class="piece-chip">
                                    <span class="piece-icon">🔩</span>
                                    <div>
                                        <div style="font-weight: 600; color: #1e293b; font-size: 0.8rem;">{{ $reference->piece->nom }}</div>
                                        @if($reference->piece->reference_oem)
                                            <div style="font-size: 0.68rem; color: #94a3b8;">OEM: {{ $reference->piece->reference_oem }}</div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <span style="color: #cbd5e1; font-size: 0.78rem;">—</span>
                            @endif
                        </td>
                        <td style="padding: 0.9rem 1rem; min-width: 200px;">
                            @php $voitureCount = $reference->voitures->count(); @endphp
                            @if($voitureCount === 0)
                                <span style="color: #cbd5e1; font-size: 0.78rem;">Aucune</span>
                            @else
                                <div class="voiture-pills" id="pills-{{ $reference->id }}">
                                    @foreach($reference->voitures->take(2) as $v)
                                        <span class="pill pill-blue" title="{{ $v->motorisation }} – {{ $v->puissance }}ch">
                                            🚗 {{ $v->marque }} {{ $v->modele }}
                                            @if($v->annee_debut)<small style="opacity:.7"> ({{ $v->annee_debut }})</small>@endif
                                        </span>
                                    @endforeach
                                    @if($voitureCount > 2)
                                        <div class="voiture-list-hidden" id="extra-{{ $reference->id }}">
                                            @foreach($reference->voitures->skip(2) as $v)
                                                <span class="pill pill-blue" style="margin-top:3px;" title="{{ $v->motorisation }} – {{ $v->puissance }}ch">
                                                    🚗 {{ $v->marque }} {{ $v->modele }}
                                                    @if($v->annee_debut)<small style="opacity:.7"> ({{ $v->annee_debut }})</small>@endif
                                                </span>
                                            @endforeach
                                        </div>
                                        <button class="voiture-expand-btn" onclick="toggleExtra({{ $reference->id }}, this)">+{{ $voitureCount - 2 }} de plus</button>
                                    @endif
                                </div>
                                <div style="font-size: 0.7rem; color: #94a3b8; margin-top: 3px;">{{ $voitureCount }} voiture(s) compatible(s)</div>
                            @endif
                        </td>
                        <td style="padding: 0.9rem 1rem;">
                            @php $stock = $reference->stock; @endphp
                            @if($stock <= 0) <span class="pill pill-red">⚠ 0</span>
                            @elseif($stock < 10) <span class="pill pill-red">{{ $stock }}</span>
                            @elseif($stock < 50) <span style="color: #b45309; font-weight: 700; font-size: 0.875rem;">{{ $stock }}</span>
                            @else <span class="pill pill-green">{{ $stock }}</span> @endif
                        </td>
                        <td style="padding: 0.9rem 1rem; font-weight: 700; color: #1e293b; font-size: 0.875rem;">{{ number_format($reference->prix, 2, ',', ' ') }} €</td>
                        <td style="padding: 0.9rem 1rem;">
                            @if($reference->is_active) <span class="pill pill-green">● Actif</span>
                            @else <span class="pill pill-red">● Inactif</span> @endif
                        </td>
                        <td style="padding: 0.9rem 1rem; text-align: right;">
                            <div style="display: flex; gap: 0.4rem; justify-content: flex-end; flex-wrap: wrap;">
                                <a href="{{ route('admin.references.show', $reference) }}" style="padding: 4px 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #475569; text-decoration: none; font-size: 0.78rem; font-weight: 600; white-space: nowrap;">Détails</a>
                                <a href="{{ route('admin.references.edit', $reference) }}" style="padding: 4px 10px; background: #eff6ff; border-radius: 6px; color: #2563eb; text-decoration: none; font-size: 0.78rem; font-weight: 600; white-space: nowrap;">Modifier</a>
                                <form action="{{ route('admin.references.destroy', $reference) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette référence ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding: 4px 10px; border: none; border-radius: 6px; color: white; background: #1e293b; font-size: 0.78rem; font-weight: 600; cursor: pointer; white-space: nowrap;">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $references->links() }}
    </div>
@endif
