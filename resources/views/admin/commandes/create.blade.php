<x-app-layout>
    @section('page-title', 'Créer une Commande')
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        .select2-dropdown {
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>

    <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); max-width: 1000px; margin: 0 auto;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h3 style="font-size: 1.1rem; font-weight: 600; color: #1e293b;">Nouvelle Commande</h3>
            <a href="{{ route('admin.commandes.index') }}" style="color: #64748b; text-decoration: none; font-size: 0.875rem;">← Retour</a>
        </div>

        <form action="{{ route('admin.commandes.store') }}" method="POST" id="order-form">
            @csrf

            <!-- Section 1:Client -->
            <div style="margin-bottom: 2rem; padding: 1.5rem; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                <h4 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem;">1. Sélectionner le Client</h4>
                
                <div id="section-existing-client">
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Client</label>
                    <select name="user_id" id="client-select" required style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem; background: white;">
                        <option value="">-- Choisir un client existant --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->nom }} {{ $user->prenom }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Section 2 : Statuts -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Statut de paiement</label>
                    <select name="payment_status" style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem; background: white;">
                        <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="paid" {{ old('payment_status', 'paid') == 'paid' ? 'selected' : '' }}>Payé</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Statut de la commande</label>
                    <select name="statut" style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem; background: white;">
                        <option value="en_attente" {{ old('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="confirmee" {{ old('statut', 'confirmee') == 'confirmee' ? 'selected' : '' }}>Confirmée / Payée</option>
                        <option value="expediee" {{ old('statut') == 'expediee' ? 'selected' : '' }}>Expédiée</option>
                        <option value="livree" {{ old('statut') == 'livree' ? 'selected' : '' }}>Livrée</option>
                    </select>
                </div>
            </div>

            <!-- Section 3 : Lignes de commande -->
            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem;">2. Produits</h4>
                
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 1rem;" id="products-table">
                    <thead>
                        <tr style="background: #f1f5f9; text-align: left;">
                            <th style="padding: 0.75rem; font-size: 0.8rem; color: #475569;">Référence - Pièce (Stock | Prix unit.)</th>
                            <th style="padding: 0.75rem; font-size: 0.8rem; color: #475569; width: 100px;">P. Revient</th>
                            <th style="padding: 0.75rem; font-size: 0.8rem; color: #475569; width: 100px;">Prix HT</th>
                            <th style="padding: 0.75rem; font-size: 0.8rem; color: #475569; width: 100px;">Prix TTC</th>
                            <th style="padding: 0.75rem; font-size: 0.8rem; color: #475569; width: 100px;">Quantité</th>
                            <th style="padding: 0.75rem; font-size: 0.8rem; color: #475569; width: 120px; text-align: right;">Total (HT / TTC)</th>
                            <th style="padding: 0.75rem; width: 60px;"></th>
                        </tr>
                    </thead>
                    <tbody id="products-body">
                        <!-- Les lignes seront ajoutées ici via JS -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <button type="button" onclick="addProductRow()" style="margin-top: 0.5rem; padding: 0.5em 1em; background: #e2e8f0; border: none; border-radius: 4px; color: #334155; font-weight: 600; cursor: pointer;">
                                    + Ajouter une référence
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="padding: 0.5rem 1rem; text-align: right; font-weight: 600; font-size: 0.9rem; color: #64748b;">TOTAL HT :</td>
                            <td style="padding: 0.5rem 1rem; text-align: right; font-weight: 600; font-size: 1rem; color: #475569;" id="total-ht">0.00 MAD</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="padding: 0.5rem 1rem; text-align: right; font-weight: 600; font-size: 0.9rem; color: #64748b;">TVA (20%) :</td>
                            <td style="padding: 0.5rem 1rem; text-align: right; font-weight: 600; font-size: 1rem; color: #475569;" id="total-tva">0.00 MAD</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="padding: 1rem; text-align: right; font-weight: 700; font-size: 1.1rem; color: #1e293b;">TOTAL TTC :</td>
                            <td style="padding: 1rem; text-align: right; font-weight: 800; font-size: 1.2rem; color: #e11d48;" id="grand-total">0.00 MAD</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Notes -->
            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Notes client / Remarques (Optionnel)</label>
                <textarea name="notes_client" rows="3" style="width: 100%; padding: 0.65rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.9rem;">{{ old('notes_client') }}</textarea>
            </div>

            <div style="text-align: right;">
                <a href="{{ route('admin.commandes.index') }}" style="margin-right: 1rem; text-decoration: none; color: #64748b; font-weight: 600;">Annuler</a>
                <button type="submit" style="padding: 0.75rem 2rem; background: #2563eb; color: white; border: none; border-radius: 6px; font-weight: 700; cursor: pointer; font-size: 1rem;">Créer la commande & Générer Facture</button>
            </div>

        </form>
    </div>

    <script>
        @php
            $mappedRefs = $references->map(function($r) {
                return [
                    'id' => $r->id,
                    'label' => $r->reference . ' - ' . ($r->nom ?? $r->piece->nom),
                    'stock' => $r->stock,
                    'prix' => $r->prix_vente ?? $r->piece->prix,
                    'revient' => $r->prix_revient
                ];
            })->values()->all();
        @endphp
        const references = @json($mappedRefs);
        let rowCount = 0;

        function addProductRow() {
            const tbody = document.getElementById('products-body');
            const tr = document.createElement('tr');
            tr.style.borderBottom = "1px solid #f1f5f9";
            
            let optionsHtml = '<option value="">-- Choisir une référence --</option>';
            references.forEach(ref => {
                optionsHtml += `<option value="${ref.id}" data-prix="${ref.prix}" data-stock="${ref.stock}" data-revient="${ref.revient}">
                    ${ref.label} (Stock: ${ref.stock} | ${Number(ref.prix).toFixed(2)}MAD)
                </option>`;
            });

            tr.innerHTML = `
                <td style="padding: 0.75rem;">
                    <select name="references[${rowCount}][id]" required onchange="updateLineTotal(this)" class="reference-select"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 0.85rem; background: white;">
                        ${optionsHtml}
                    </select>
                </td>
                <td style="padding: 0.75rem;">
                    <input type="number" step="0.01" name="references[${rowCount}][prix_revient]" value="0" min="0" required 
                           style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 0.85rem; text-align: center; background: #f8fafc; color: #64748b;">
                </td>
                <td style="padding: 0.75rem;">
                    <input type="number" step="0.01" name="references[${rowCount}][prix]" value="0" min="0" required onchange="updateLineTotal(this)"
                           style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 0.85rem; text-align: center;">
                </td>
                <td style="padding: 0.75rem; text-align: center; color: #64748b; font-size: 0.85rem;" class="line-ttc">
                    0.00
                </td>
                <td style="padding: 0.75rem;">
                    <input type="number" name="references[${rowCount}][quantite]" value="1" min="1" required onchange="updateLineTotal(this)"
                           style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 0.85rem; text-align: center;">
                </td>
                <td style="padding: 0.75rem; text-align: right;">
                    <div style="font-size: 0.75rem; color: #64748b;" class="line-total-ht">0.00 MAD HT</div>
                    <div style="font-weight: 700; color: #1e293b; font-size: 0.85rem;" class="line-total-ttc">0.00 MAD TTC</div>
                </td>
                <td style="padding: 0.75rem; text-align: right;">
                    <button type="button" onclick="this.closest('tr').remove(); calculateGrandTotal();" style="color: #ef4444; background: none; border: none; cursor: pointer; font-size: 1.2rem;">×</button>
                </td>
            `;

            tbody.appendChild(tr);
            
            // Initialiser Select2 pour la nouvelle ligne
            $(tr).find('.reference-select').select2({
                placeholder: "-- Choisir une référence --",
                allowClear: true,
                width: '100%'
            }).on('change', function() {
                updateLineTotal(this);
            });

            rowCount++;
        }

        function updateLineTotal(element) {
            const tr = element.closest('tr');
            const select = tr.querySelector('select');
            const inputs = tr.querySelectorAll('input[type="number"]');
            const prixRevientInput = inputs[0];
            const prixHtInput = inputs[1];
            const qtyInput = inputs[2];
            
            const selectedOption = select.options[select.selectedIndex];
            
            if (element === select && selectedOption.value) {
                // Auto-fill HT price from DB (now stored as HT in reference->prix)
                const htPrice = parseFloat(selectedOption.getAttribute('data-prix'));
                prixHtInput.value = htPrice.toFixed(2);
                
                // Auto-fill Revient HT from DB
                prixRevientInput.value = parseFloat(selectedOption.getAttribute('data-revient')).toFixed(2);
            }
            
            if (selectedOption.value) {
                const stock = parseInt(selectedOption.getAttribute('data-stock'));
                const qty = parseInt(qtyInput.value) || 0;
                const prixHt = parseFloat(prixHtInput.value) || 0;
                
                if (qty > stock) {
                    alert(`Attention: Le stock disponible pour ce produit est de ${stock}.`);
                    qtyInput.value = stock;
                }
                
                const ttcUnit = prixHt * 1.2;
                tr.querySelector('.line-ttc').innerText = ttcUnit.toFixed(2);

                const totalHt = prixHt * parseInt(qtyInput.value);
                const totalTtc = ttcUnit * parseInt(qtyInput.value);

                tr.querySelector('.line-total-ht').innerText = totalHt.toFixed(2) + ' MAD HT';
                tr.querySelector('.line-total-ttc').innerText = totalTtc.toFixed(2) + ' MAD TTC';
            } else {
                tr.querySelector('.line-ttc').innerText = '0.00';
                tr.querySelector('.line-total-ht').innerText = '0.00 MAD HT';
                tr.querySelector('.line-total-ttc').innerText = '0.00 MAD TTC';
            }
            
            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            let totalTtc = 0;
            const selects = document.querySelectorAll('#products-body select');
            
            selects.forEach(select => {
                const selectedOption = select.options[select.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    const inputs = select.closest('tr').querySelectorAll('input[type="number"]');
                    const prixHt = parseFloat(inputs[1].value); // Index 1 est le prix vente HT
                    const qty = parseInt(inputs[2].value); // Index 2 est la quantité
                    if(!isNaN(prixHt) && !isNaN(qty)) {
                        totalTtc += (prixHt * 1.2) * qty;
                    }
                }
            });
            
            document.getElementById('grand-total').innerText = totalTtc.toFixed(2) + ' MAD';
            
            const totalHt = totalTtc / 1.2;
            const tva = totalTtc - totalHt;
            
            document.getElementById('total-ht').innerText = totalHt.toFixed(2) + ' MAD';
            document.getElementById('total-tva').innerText = tva.toFixed(2) + ' MAD';
        }

        // Add one empty row on load
        window.addEventListener('DOMContentLoaded', () => {
            // Charger jQuery si pas présent (Select2 en a besoin)
            if (typeof jQuery === 'undefined') {
                const script = document.createElement('script');
                script.src = "https://code.jquery.com/jquery-3.6.0.min.js";
                script.onload = () => {
                    const s2script = document.createElement('script');
                    s2script.src = "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js";
                    s2script.onload = initSelect2;
                    document.head.appendChild(s2script);
                };
                document.head.appendChild(script);
            } else {
                initSelect2();
            }
        });

        function initSelect2() {
            $('#client-select').select2({
                placeholder: "-- Choisir un client existant --",
                allowClear: true,
                width: '100%'
            });
            addProductRow();
        }
    </script>
</x-app-layout>
