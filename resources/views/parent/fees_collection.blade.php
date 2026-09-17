@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>Fees Collection <span style="color:blue;">({{ $getStudent->name }}
                                {{ $getStudent->last_name }})</span></h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        @include('message')
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Fees Payment Details</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Class Name</th>
                                            <th>Total Fee Amount</th>
                                            <th>Paid Fee Amount</th>
                                            <th>Remaining Fee Amount</th>
                                            <th>Payment Type</th>
                                            <th>Remark</th>
                                            <th>Created By</th>
                                            <th>Created Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getFees as $value)
                                            <tr>
                                                <td>{{ $value->class_name }}</td>
                                                <td>₹{{ number_format($value->total_fee_amount, 2) }}</td>
                                                <td>₹{{ number_format($value->paid_fee_amount, 2) }}</td>
                                                <td>₹{{ number_format($value->remaining_fee_amount, 2) }}</td>
                                                <td>{{ $value->payment_type }}</td>
                                                <td>{{ $value->remark }}</td>
                                                <td>{{ $value->created_by_name }}</td>
                                                <td>{{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%" style="text-align: center">You are not paid your son's/daughter's fee</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
