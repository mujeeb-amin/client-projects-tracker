<!doctype html>
<html lang="en"><head><meta charset="utf-8"><style>
body{font-family:DejaVu Sans,sans-serif;font-size:12px;color:#18283e;padding:26px}
h1{font-size:21px;margin:0 0 5px;color:#243b83}.subtitle{color:#64748b;margin-bottom:25px}
table{width:100%;border-collapse:collapse}th,td{border:1px solid #cbd5e1;padding:11px;text-align:left;vertical-align:top}th{width:35%;background:#eef2ff}
.total{font-weight:bold;color:#15803d}.footer{margin-top:24px;color:#64748b;font-size:10px}
</style></head><body>
<h1>CLIENT PROJECTS TRACKER</h1>
<p class="subtitle">Payment Receipt #{{ $payment->id }}</p>
<table><tbody>
<tr><th>Client</th><td>{{ $project->client?->name ?? '—' }}</td></tr>
<tr><th>Project</th><td>{{ $project->title }}</td></tr>
<tr><th>Task</th><td>{{ $payment->task ?: '—' }}</td></tr>
<tr><th>Payment Date</th><td>{{ $payment->payment_date }}</td></tr>
<tr><th>Payment Amount</th><td class="total">${{ number_format($payment->amount, 2) }}</td></tr>
<tr><th>Project Price</th><td>${{ number_format($project->agreed_price, 2) }}</td></tr>
<tr><th>Total Received</th><td>${{ number_format($totalReceived, 2) }}</td></tr>
<tr><th>Remaining Balance</th><td>${{ number_format($balance, 2) }}</td></tr>
<tr><th>Note</th><td>{{ $payment->note ?: '—' }}</td></tr>
</tbody></table>
<p class="footer">Generated from the current payment and project records.</p>
</body></html>
