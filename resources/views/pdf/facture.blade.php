<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture {{ $commande->numero_commande }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #333;
            font-size: 14px;
            margin: 0;
            padding: 20px;
        }
        .header {
            width: 100%;
            margin-bottom: 30px;
        }
        .header td {
            vertical-align: top;
        }
        .company-info {
            text-align: right;
        }
        h1 {
            color: #e11d48;
            margin-bottom: 5px;
            font-size: 24px;
        }
        .facture-details {
            margin-top: 30px;
            margin-bottom: 30px;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.items th, table.items td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table.items th {
            background-color: #f8f9fa;
        }
        .totals {
            width: 50%;
            float: right;
            border-collapse: collapse;
        }
        .totals td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .totals .final-total {
            font-weight: bold;
            font-size: 18px;
            color: #e11d48;
            border-bottom: 2px solid #e11d48;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #777;
            clear: both;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td width="50%">
                <h1>AUTOPARTS</h1>
                <p>
                    123 rue de la Mécanique<br>
                    75000 Paris, France<br>
                    Tél : 01 23 45 67 89<br>
                    contact@autoparts.com
                </p>
            </td>
            <td width="50%" class="company-info">
                <h2>FACTURE</h2>
                <p>
                    <strong>N° :</strong> {{ $commande->numero_commande }}<br>
                    <strong>Date :</strong> {{ $commande->created_at->format('d/m/Y') }}
                </p>
            </td>
        </tr>
    </table>

    <div class="facture-details">
        <h3>Facturé à :</h3>
        <p>
            <strong>{{ $commande->user->prenom }} {{ $commande->user->nom }}</strong><br>
            {{ $commande->user->adresse_livraison }}<br>
            {{ $commande->user->code_postal }} {{ $commande->user->ville }}<br>
            {{ $commande->user->email }}<br>
            {{ $commande->user->telephone }}
        </p>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Ref.OEM</th>
                <th>Désignation</th>
                <th>Qté</th>
                <th>P.U. TTC</th>
                <th style="text-align: right;">Total TTC</th>
            </tr>
        </thead>
        <tbody>
            @foreach($commande->references as $ligne)
            <tr>
                <td style="font-family: monospace;">{{ $ligne->reference->reference }}</td>
                <td>
                    <strong>{{ $ligne->reference->piece->nom ?? 'Pièce' }}</strong><br>
                    {{ $ligne->reference->nom }}
                </td>
                <td style="text-align: center;">{{ $ligne->quantite }}</td>
                <td>{{ number_format($ligne->prix_unitaire, 2) }} €</td>
                <td style="text-align: right;">{{ number_format($ligne->total_ligne, 2) }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Sous-total (HT)</td>
            <td style="text-align: right;">{{ number_format($commande->total / 1.2, 2) }} €</td>
        </tr>
        <tr>
            <td>TVA (20%)</td>
            <td style="text-align: right;">{{ number_format($commande->total - ($commande->total / 1.2), 2) }} €</td>
        </tr>
        <tr class="final-total">
            <td>TOTAL TTC</td>
            <td style="text-align: right;">{{ number_format($commande->total, 2) }} €</td>
        </tr>
    </table>

    <div class="footer">
        <p>Merci pour votre commande sur AutoParts.</p>
        <p>Siret : 123 456 789 00012 - Capital social : 100 000 €</p>
    </div>

</body>
</html>
