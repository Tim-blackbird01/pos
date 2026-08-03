@extends('layouts.app')

@section('title', __('mpesa::lang.transactions'))

@section('content')
<section class="content-header">
    <h1>@lang('mpesa::lang.mpesa') - @lang('mpesa::lang.transactions')</h1>
</section>

<section class="content">
    <div class="box box-primary">
        <div class="box-body table-responsive">

            <form method="GET" action="{{ action([\Modules\Mpesa\Http\Controllers\MpesaController::class, 'transactions']) }}" class="form-inline" style="margin-bottom: 15px;">
                <div class="form-group">
                    <input type="text" name="search" class="form-control" style="width: 280px;"
                        placeholder="Search invoice #, M-Pesa receipt, or phone"
                        value="{{ $search ?? '' }}">
                </div>
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                @if(!empty($search))
                    <a href="{{ action([\Modules\Mpesa\Http\Controllers\MpesaController::class, 'transactions']) }}" class="btn btn-default">Clear</a>
                @endif
            </form>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>@lang('mpesa::lang.date')</th>
                        <th>@lang('mpesa::lang.type')</th>
                        <th>@lang('mpesa::lang.phone')</th>
                        <th>@lang('mpesa::lang.amount')</th>
                        <th>@lang('mpesa::lang.mpesa_receipt_number')</th>                        
                        <th>Invoice #</th>
                        <th>@lang('mpesa::lang.reference')</th>
                        <th>@lang('mpesa::lang.status')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $txn)
                        <tr>
                            <td>{{ $txn->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ strtoupper($txn->type) }}</td>
                            <td>{{ $txn->phone_number }}</td>
                            <td>{{ number_format($txn->amount, 2) }}</td>
                            <td>{{ $txn->mpesa_receipt_number ?? '-' }}</td>
                            <td>
                                @if(!empty($txn->invoice_no))
                                    {{ $txn->invoice_no }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $txn->account_reference ?? '-' }}</td>
                            <td>
                                @if($txn->status == 'success')
                                    <span class="label label-success">{{ ucfirst($txn->status) }}</span>
                                @elseif($txn->status == 'pending')
                                    <span class="label label-warning">{{ ucfirst($txn->status) }}</span>
                                @else
                                    <span class="label label-danger">{{ ucfirst($txn->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No M-Pesa transactions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $transactions->links() }}
        </div>
    </div>
</section>
@endsection
