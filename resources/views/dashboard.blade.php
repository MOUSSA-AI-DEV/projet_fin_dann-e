<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom: 1rem; color: #1e293b;">Bienvenue, {{ Auth::user()->prenom }} {{ Auth::user()->nom }} !</h2>
        <p style="color: #64748b;">Vous êtes connecté avec le rôle : <strong>{{ Auth::user()->role }}</strong></p>
    </div>
</x-app-layout>
