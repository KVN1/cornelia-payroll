@extends('layouts.app')
@section('page-title', 'Payroll')
@section('content')

<div class="flex items-center justify-between mb-4">
    <div>
        <div class="section-title">Payroll Periods</div>
        <div class="section-sub">Semi-monthly payroll for all active employees.</div>
    </div>
    <a href="{{ route('payroll.create') }}" class="btn btn-primary">+ New Period</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Period</th><th>Pay Date</th><th>Status</th><th>Records</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($periods as $period)
                <tr>
                    <td><strong>{{ $period->period_start->format('M d') }} – {{ $period->period_end->format('M d, Y') }}</strong></td>
                    <td>{{ $period->pay_date->format('M d, Y') }}</td>
                    <td><span class="badge badge-{{ $period->status }}">{{ ucfirst($period->status) }}</span></td>
                    <td>{{ $period->records_count }} employees</td>
                    <td class="flex gap-2">
                        <a href="{{ route('payroll.show', $period) }}" class="btn btn-outline btn-sm">View</a>
                        @if($period->status === 'open')
                        <form method="POST" action="{{ route('payroll.generate', $period) }}">
                            @csrf
                            <button class="btn btn-caramel btn-sm" onclick="return confirm('Generate payroll for all active employees?')">⚙ Generate</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-muted" style="text-align:center;padding:32px">No payroll periods yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:0 20px">{{ $periods->links() }}</div>
</div>
@endsection