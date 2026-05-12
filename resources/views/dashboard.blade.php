<x-app-layout>
    @section('page-title', 'Tableau de bord')

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Welcome Card -->
        <div class="fluid-card" style="background: linear-gradient(135deg, #FFFFFF 0%, #EFF6FF 100%); border-left: 5px solid var(--primary);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;">Bienvenue</div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: var(--text-main);">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
                    <div style="margin-top: 1rem; font-size: 0.85rem; color: var(--primary); font-weight: 600;">Administrateur Système 🛠️</div>
                </div>
                <div style="font-size: 2rem; opacity: 0.5;">👤</div>
            </div>
        </div>

        <!-- Role Card -->
        <div class="fluid-card" style="background: linear-gradient(135deg, #FFFFFF 0%, #F0FDF4 100%); border-left: 5px solid var(--success);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;">Habilitation</div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: var(--text-main);">{{ ucfirst(Auth::user()->role) }}</div>
                    <div style="margin-top: 1rem; font-size: 0.85rem; color: var(--success); font-weight: 600;">Accès Complet ✅</div>
                </div>
                <div style="font-size: 2rem; opacity: 0.5;">🔑</div>
            </div>
        </div>

        <!-- Session Card -->
        <div class="fluid-card" style="background: linear-gradient(135deg, #FFFFFF 0%, #FFFBEB 100%); border-left: 5px solid var(--accent);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;">Email Connecté</div>
                    <div style="font-size: 1.05rem; font-weight: 700; color: var(--text-main); word-break: break-all;">{{ Auth::user()->email }}</div>
                    <div style="margin-top: 1rem; font-size: 0.85rem; color: var(--accent); font-weight: 600;">Connexion Sécurisée 🔒</div>
                </div>
                <div style="font-size: 2rem; opacity: 0.5;">📧</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="fluid-card section-glow" style="min-height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; background: white;">
        <div style="width: 80px; height: 80px; background: var(--primary-glow); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin-bottom: 1.5rem; color: var(--primary);">🚀</div>
        <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.75rem;">Plateforme de Gestion</h3>
        <p style="color: var(--text-muted); max-width: 500px; margin-bottom: 2rem; line-height: 1.6;">
            Bienvenue dans votre centre de contrôle <b>AutoParts</b>. Vous pouvez gérer le catalogue, suivre les commandes et analyser les performances depuis le menu latéral.
        </p>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center;">
            <a href="{{ route('admin.references.index') }}" class="fluid-btn fluid-btn-primary">📦 Catalogue de Pièces</a>
            <a href="{{ route('client.catalogue.index') }}" target="_blank" class="fluid-btn" style="background: #F1F5F9; color: var(--text-main);">🌐 Voir la Boutique</a>
        </div>
    </div>
</x-app-layout>
