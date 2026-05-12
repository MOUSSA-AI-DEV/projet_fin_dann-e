<x-app-layout>
    @section('page-title', 'Gestion des Références')

    <style>
        /* ── Search bar ── */
        .search-wrap {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            padding: 0 0.75rem;
            flex: 1;
            max-width: 420px;
            transition: border-color 0.15s;
        }
        .search-wrap:focus-within { border-color: #2563eb; background: #fff; }
        .search-wrap input {
            border: none; background: transparent; outline: none;
            font-size: 0.875rem; padding: 0.55rem 0; flex: 1; color: #1e293b;
        }
        .search-wrap .search-icon { color: #94a3b8; font-size: 1rem; }

        .btn-search {
            padding: 0.55rem 1.1rem;
            background: #1e293b; color: white;
            border: none; border-radius: 8px;
            font-size: 0.875rem; font-weight: 600;
            cursor: pointer; transition: background 0.15s;
        }
        .btn-search:hover { background: #334155; }

        .btn-reset {
            padding: 0.55rem 0.9rem;
            background: #fee2e2; color: #991b1b;
            border: none; border-radius: 8px;
            font-size: 0.8rem; font-weight: 600;
            cursor: pointer; text-decoration: none;
            display: flex; align-items: center; gap: 4px;
        }

        /* ── Search result highlight ── */
        .search-result-banner {
            background: #eff6ff; border: 1px solid #bfdbfe;
            color: #1d4ed8; border-radius: 8px;
            padding: 0.6rem 1rem; font-size: 0.875rem; font-weight: 500;
            margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;
        }

        /* ── Voitures pill list ── */
        .voiture-pills { display: flex; flex-wrap: wrap; gap: 4px; }
        .pill {
            display: inline-flex; align-items: center; gap: 3px;
            padding: 2px 8px; border-radius: 20px;
            font-size: 0.7rem; font-weight: 600; white-space: nowrap;
        }
        .pill-blue  { background: #eff6ff; color: #2563eb; }
        .pill-gray  { background: #f1f5f9; color: #64748b; }
        .pill-green { background: #dcfce7; color: #166534; }
        .pill-red   { background: #fee2e2; color: #991b1b; }

        /* ── Piece chip ── */
        .piece-chip {
            display: inline-flex; align-items: center; gap: 5px;
            background: #fafaf9; border: 1px solid #e7e5e4;
            border-radius: 6px; padding: 3px 8px; font-size: 0.78rem;
        }
        .piece-chip .piece-icon { font-size: 0.9rem; }

        tr.ref-row:hover { background: #f8fafc; }

        /* expand/collapse rows */
        .voiture-expand-btn {
            background: none; border: none; cursor: pointer;
            color: #2563eb; font-size: 0.72rem; font-weight: 700;
            padding: 0; text-decoration: underline; white-space: nowrap;
        }
        .voiture-list-hidden { display: none; }

        /* ── Import Modal ── */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5);
            align-items: center; justify-content: center;
        }
        .modal-content {
            background-color: #fefefe;
            padding: 2rem;
            border-radius: 12px;
            width: 450px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            position: relative;
        }
        .modal-header {
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 0.75rem;
        }
        .modal-header h4 { margin: 0; font-size: 1.25rem; color: #1e293b; }
        .close-modal {
            position: absolute; right: 1.5rem; top: 1.5rem;
            font-size: 1.5rem; cursor: pointer; color: #64748b;
        }
        .file-upload-box {
            border: 2px dashed #cbd5e1;
            padding: 2rem;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .file-upload-box:hover { border-color: #2563eb; background: #f8fafc; }
        .file-upload-box input { display: none; }
    </style>


    <div class="fluid-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-main); margin: 0;">📦 Liste des Références</h3>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <button type="button" onclick="openImportModal()" class="fluid-btn" style="background: #64748b; color: white;">📥 Importer</button>
                <a href="{{ route('admin.references.create') }}" class="fluid-btn fluid-btn-primary">+ Nouvelle Référence</a>
            </div>
        </div>

        <!-- Paramètres de Tarification Générale -->
        <div style="background: #f1f5f9; padding: 1.5rem; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 0;">⚙️ Paramètres de Tarification Générale</h4>
                <span style="font-size: 0.75rem; color: #64748b;">Ces valeurs seront utilisées par défaut lors de la création de nouvelles références.</span>
            </div>
            
            <form action="{{ route('admin.references.update-global-pricing') }}" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; align-items: flex-end;">
                @csrf
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Coeff. Bénéficiaire par défaut</label>
                    <input type="number" step="0.01" name="coefficient" value="{{ $globalCoeff }}" 
                           style="width: 100%; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Coeff. Charges par défaut <small style="color:#94a3b8;">(0.10 = 10%)</small></label>
                    <input type="number" step="0.01" name="coeff_charges" value="{{ $globalCoeffCharges }}" 
                           style="width: 100%; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem;">
                </div>
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="fluid-btn" style="background: #1e293b; color: white;">Enregistrer</button>
                    <button type="submit" name="apply_to_all" value="1" class="fluid-btn" style="background: #ef4444; color: white;" 
                            onclick="return confirm('Attention : Cela va écraser les prix de TOUTES les références existantes. Continuer ?')">
                        Appliquer à TOUS
                    </button>
                </div>
            </form>
        </div>

        <form method="GET" action="{{ route('admin.references.index') }}" id="search-form" style="display: flex; gap: 1rem; align-items: center; margin-bottom: 2rem; flex-wrap: wrap;">
            <div class="search-wrap" style="flex: 1; min-width: 300px;">
                <span class="search-icon">🔍</span>
                <input type="text" name="search" id="search-input" class="fluid-input" style="padding-left: 2.5rem;" value="{{ $search }}" placeholder="Rechercher par code, nom, pièce, marque..." autocomplete="off">
            </div>
            <button type="submit" class="fluid-btn fluid-btn-primary">Rechercher</button>
            <a href="{{ route('admin.references.index') }}" id="reset-link" style="display: {{ $search ? 'inline-flex' : 'none' }};" class="fluid-btn">✕ Effacer</a>
        </form>


        <div id="table-container">
            @include('admin.references._table')
        </div>
    </div>

    <script>
        function toggleExtra(id, btn) {
            const extra = document.getElementById('extra-' + id);
            const hidden = extra.classList.contains('voiture-list-hidden');
            if (hidden) {
                extra.classList.remove('voiture-list-hidden');
                extra.style.display = 'flex';
                extra.style.flexWrap = 'wrap';
                extra.style.gap = '4px';
                extra.style.marginTop = '4px';
                btn.textContent = 'Réduire';
            } else {
                extra.classList.add('voiture-list-hidden');
                extra.style.display = 'none';
                const count = extra.querySelectorAll('.pill').length;
                btn.textContent = '+' + count + ' de plus';
            }
        }

        const searchInput = document.getElementById('search-input');
        const container = document.getElementById('table-container');
        const resetLink = document.getElementById('reset-link');
        let debounceTimer;

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const query = this.value;
                
                // Show/hide reset button
                resetLink.style.display = query.trim() !== '' ? 'flex' : 'none';
                
                performSearch(query);
            }, 300);
        });

        document.getElementById('search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            performSearch(searchInput.value);
        });

        async function performSearch(query) {
            try {
                const response = await fetch(`{{ route('admin.references.index') }}?search=${encodeURIComponent(query)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!response.ok) throw new Error('Network response was not ok');
                const html = await response.text();
                container.innerHTML = html;
                
                // update la bare pour visualisation de localisation
                const url = new URL(window.location);
                if (query) url.searchParams.set('search', query);
                else url.searchParams.delete('search');
                window.history.replaceState({}, '', url);
            } catch (error) {
                console.error('Search error:', error);
            }
        }
    </script>

    <!-- Import Modal -->
    <div id="importModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <span class="close-modal" onclick="closeImportModal()">&times;</span>
            <div class="modal-header">
                <h4>Importer des Références / Facture Fournisseur</h4>
            </div>
            <form action="{{ route('admin.references.import') }}" method="POST" enctype="multipart/form-data" style="margin-top: 1rem;">
                @csrf
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 0.4rem;">N° Facture</label>
                        <input type="text" name="numero_facture" required class="fluid-input" placeholder="EX: FAC-2024-001">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Fournisseur</label>
                        <input type="text" name="fournisseur" required class="fluid-input" placeholder="Nom du fournisseur">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Date Facture</label>
                        <input type="date" name="date_facture" required class="fluid-input" value="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Taux Conversion (€ → MAD)</label>
                        <input type="number" step="0.01" name="taux_conversion" required class="fluid-input" value="11.00">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; padding: 1rem; background: #f8fafc; border-radius: 8px;">
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Coeff. Charges</label>
                        <input type="number" step="0.01" name="coeff_charges" required class="fluid-input" value="{{ $globalCoeffCharges }}">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Coeff. Bénéficiaire</label>
                        <input type="number" step="0.01" name="coeff_benef" required class="fluid-input" value="{{ $globalCoeff }}">
                    </div>
                </div>

                <div class="file-upload-box" onclick="document.getElementById('import-file').click()" style="margin-bottom: 1.5rem;">
                    <span style="font-size: 2rem;">📊</span>
                    <p style="margin-top: 1rem; font-size: 0.9rem; color: #475569;">Cliquez pour choisir le fichier d'importation <br><b>(XLSX, XLS, CSV)</b></p>
                    <span id="file-name" style="display: block; margin-top: 0.5rem; font-size: 0.8rem; color: #2563eb; font-weight: 600;"></span>
                    <input type="file" name="file" id="import-file" required onchange="handleFileSelect(this)" style="display: none;">
                </div>

                <button type="submit" class="fluid-btn fluid-btn-primary" style="width: 100%; justify-content: center; height: 3rem; font-size: 1rem;">Démarrer l'importation et Créer Facture</button>
            </form>
        </div>
    </div>

    <script>
        function openImportModal() {
            document.getElementById('importModal').style.display = 'flex';
        }
        function closeImportModal() {
            document.getElementById('importModal').style.display = 'none';
        }
        function handleFileSelect(input) {
            const fileName = input.files[0] ? input.files[0].name : '';
            document.getElementById('file-name').textContent = fileName;
        }
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('importModal');
            if (event.target == modal) {
                closeImportModal();
            }
        }
    </script>
</x-app-layout>

