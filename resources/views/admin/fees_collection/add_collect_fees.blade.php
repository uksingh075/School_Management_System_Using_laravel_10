@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Collect Fees <span style="color: blue;">({{ $getStudent->name }}
                                {{ $getStudent->last_name }})</span></h1>
                    </div>
                    <div class="col-sm-6" style="text-align: right">
                        <button href="" class="btn btn-primary" id="AddFees">Add Fees</button>
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
                                <h3 class="card-title">Payment Details</h3>
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
                                                <td colspan="100%" style="text-align: center">Records Not Found</td>
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

    <div class="modal fade" id="AddFeesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Fees</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-form-label">Class Name: {{ $getStudent->class_name }}</label>
                        </div>

                        <div class="form-group">
                            <label class="col-form-label">Total Fee Amount:
                                ₹{{ number_format($getStudent->fee_amount, 2) }}</label>
                        </div>

                        <div class="form-group">
                            <label class="col-form-label">Paid Fee Amount:
                                ₹{{ number_format($paid_fee_amount, 2) }}</label>
                        </div>

                        <div class="form-group">
                            @php
                                $remaining_fee_amount = $getStudent->fee_amount - $paid_fee_amount;
                            @endphp
                            <label class="col-form-label">Remaining Fee
                                Amount:₹{{ number_format($remaining_fee_amount, 2) }}</label>
                        </div>

                        <div class="form-group">
                            <label class="col-form-label">Amount:</label>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror" name="amount"
                                placeholder="Enter Amount">
                            @error('amount')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="col-form-label">Payment Type:</label>
                            <select class="form-control @error('payment_type') is-invalid @enderror" name="payment_type">
                                <option value="">Select Type</option>
                                <option value="Cash">Cash</option>
                            </select>
                            @error('payment_type')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="col-form-label">Remark:</label>
                            <textarea class="form-control" name="remark"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#AddFees').click(function() {
            $('#AddFeesModal').modal('show');
        });
    </script>
@endsection
