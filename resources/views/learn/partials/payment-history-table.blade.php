@props(['payments', 'showPagination' => false])

<div class="table-responsive">
    <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
        <thead class="bg-light">
            <tr>
                <th class="border-0 ps-3 fw-semibold text-muted" style="font-size: 0.75rem;">PLAN</th>
                <th class="border-0 fw-semibold text-muted" style="font-size: 0.75rem;">AMOUNT</th>
                <th class="border-0 fw-semibold text-muted" style="font-size: 0.75rem;">TRANSACTION</th>
                <th class="border-0 fw-semibold text-muted" style="font-size: 0.75rem;">PHONE</th>
                <th class="border-0 fw-semibold text-muted" style="font-size: 0.75rem;">STATUS</th>
                <th class="border-0 fw-semibold text-muted" style="font-size: 0.75rem;">DATE</th>
                <th class="border-0 pe-3 fw-semibold text-muted text-end" style="font-size: 0.75rem;">ACTION</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $sub)
            <tr>
                <td class="ps-3">
                    <span class="fw-medium">{{ $sub->package->name ?? 'N/A' }}</span>
                    <div class="text-muted" style="font-size: 0.75rem;">{{ $sub->package->duration_text ?? '' }}</div>
                </td>
                <td>
                    <span class="fw-semibold">KES {{ number_format($sub->amount, 0) }}</span>
                </td>
                <td>
                    @if($sub->transaction_id)
                        <code class="text-dark" style="font-size: 0.8rem;">{{ $sub->transaction_id }}</code>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    @if($sub->phone_number)
                        <span>{{ $sub->phone_number }}</span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    @if($sub->isActive())
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                            <i class="bi bi-check-circle me-1"></i>Active
                        </span>
                    @elseif($sub->status === 'pending')
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1">
                            <i class="bi bi-clock me-1"></i>Pending
                        </span>
                    @elseif($sub->status === 'expired')
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1">
                            <i class="bi bi-x-circle me-1"></i>Expired
                        </span>
                    @elseif($sub->status === 'failed')
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">
                            <i class="bi bi-exclamation-circle me-1"></i>Failed
                        </span>
                    @else
                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1">
                            {{ ucfirst($sub->status) }}
                        </span>
                    @endif
                </td>
                <td>
                    <span>{{ $sub->created_at->format('M d, Y') }}</span>
                    <div class="text-muted" style="font-size: 0.75rem;">{{ $sub->created_at->format('h:i A') }}</div>
                </td>
                <td class="pe-3 text-end">
                    @if($sub->status === 'pending')
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('learn.subscription.pay', $sub) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-credit-card me-1"></i>Pay
                            </a>
                            <form action="{{ route('learn.subscription.cancel', $sub) }}" method="POST" class="d-inline cancel-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-secondary" title="Cancel">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </form>
                        </div>
                    @elseif($sub->isActive())
                        <span class="text-success" style="font-size: 0.8rem;">
                            <i class="bi bi-check-circle-fill me-1"></i>{{ $sub->expires_at->format('M d') }}
                        </span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-4 text-muted">
                    <i class="bi bi-inbox display-6 d-block mb-2"></i>
                    No payment history found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($showPagination && $payments instanceof \Illuminate\Pagination\LengthAwarePaginator)
<div class="px-3 py-3 border-top">
    {{ $payments->links() }}
</div>
@endif
