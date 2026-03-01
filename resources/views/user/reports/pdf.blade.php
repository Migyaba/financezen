<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Financier — FinanceZen</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; color: #334155; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #4F46E5; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #1E293B; font-size: 28px; margin: 0; font-weight: bold; }
        .header p { color: #64748B; font-size: 14px; margin-top: 5px; }
        
        .summary { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .summary td { width: 50%; padding: 15px; border: 1px solid #E2E8F0; vertical-align: top; }
        .summary h3 { margin: 0 0 10px 0; color: #475569; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; }
        .val { font-size: 24px; font-weight: bold; }
        .income { color: #10B981; }
        .expense { color: #EF4444; }

        h2 { font-size: 18px; color: #1E293B; margin-bottom: 15px; border-bottom: 1px solid #E2E8F0; padding-bottom: 8px; }

        table.data { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table.data th { background-color: #F8FAFC; color: #475569; font-weight: bold; text-align: left; padding: 10px; border-bottom: 2px solid #E2E8F0; font-size: 12px; text-transform: uppercase; }
        table.data td { padding: 10px; border-bottom: 1px solid #E2E8F0; font-size: 13px; }
        .text-right { text-align: right; }
        
        .footer { text-align: center; margin-top: 50px; font-size: 12px; color: #94A3B8; border-top: 1px solid #E2E8F0; padding-top: 15px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>FinanceZen</h1>
        <p>
            Rapport financier de <strong>{{ $user->name }}</strong><br>
            Période : Du {{ $startDate->format('d/m/Y') }} au {{ $endDate->format('d/m/Y') }}
        </p>
    </div>

    <table class="summary">
        <tr>
            <td>
                <h3>Total Revenus</h3>
                <div class="val income">+{{ number_format($income, 0, ',', ' ') }} FCFA</div>
            </td>
            <td>
                <h3>Total Dépenses</h3>
                <div class="val expense">-{{ number_format($expense, 0, ',', ' ') }} FCFA</div>
            </td>
        </tr>
    </table>

    <table class="summary">
        <tr>
            <td style="width: 100%; text-align: center;">
                <h3>Bilan de la Période (Solde)</h3>
                @php $balance = $income - $expense; @endphp
                <div class="val" style="color: {{ $balance >= 0 ? '#4F46E5' : '#EF4444' }};">
                    {{ $balance > 0 ? '+' : '' }}{{ number_format($balance, 0, ',', ' ') }} FCFA
                </div>
            </td>
        </tr>
    </table>

    <h2>Répartition des Dépenses (Top 10)</h2>
    <table class="data">
        <thead>
            <tr>
                <th>Catégorie</th>
                <th class="text-right">Montant</th>
                <th class="text-right">% du total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categoriesData as $cat)
            <tr>
                <td><strong>{{ $cat['name'] }}</strong></td>
                <td class="text-right expense">{{ number_format($cat['total'], 0, ',', ' ') }} FCFA</td>
                <td class="text-right">
                    {{ $expense > 0 ? round(($cat['total'] / $expense) * 100, 1) : 0 }} %
                </td>
            </tr>
            @endforeach
            @if($categoriesData->isEmpty())
            <tr><td colspan="3" style="text-align: center;">Aucune dépense sur cette période.</td></tr>
            @endif
        </tbody>
    </table>

    <h2>Détail des Transactions</h2>
    <table class="data">
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Catégorie</th>
                <th class="text-right">Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $t)
            <tr>
                <td>{{ $t->transaction_date->format('d/m/Y') }}</td>
                <td>{{ $t->description ?? '-' }}</td>
                <td>{{ $t->category->name ?? '-' }}</td>
                <td class="text-right {{ $t->type === 'income' ? 'income' : 'expense' }}">
                    {{ $t->type === 'income' ? '+' : '-' }}{{ number_format($t->amount, 0, ',', ' ') }} FCFA
                </td>
            </tr>
            @endforeach
            @if($transactions->isEmpty())
            <tr><td colspan="4" style="text-align: center;">Aucune transaction sur cette période.</td></tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Généré automatiquement par <strong>FinanceZen</strong> le {{ now()->format('d/m/Y à H:i') }}
    </div>

</body>
</html>
