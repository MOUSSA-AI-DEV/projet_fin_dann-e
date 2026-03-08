<x-app-layout>
    @section('page-title', 'Dashboard')

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-left: 4px solid #2563eb;">
            <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;">Bienvenue</div>
            <div style="font-size: 1.3rem; font-weight: 700; color: #1e293b;">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-left: 4px solid #10b981;">
            <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;">Rôle</div>
            <div style="font-size: 1.3rem; font-weight: 700; color: #1e293b;">{{ ucfirst(Auth::user()->role) }}</div>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-left: 4px solid #f59e0b;">
            <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;">Email</div>
            <div style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">{{ Auth::user()->email }}</div>
        </div>
    </div>

    <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
        <h3 style="font-size: 0.95rem; font-weight: 600; color: #475569; margin-bottom: 1rem;">Activité récente</h3>
        <p style="color: #94a3b8; font-size: 0.9rem;">Aucune activité récente.</p>
    </div>
</x-app-layout>
