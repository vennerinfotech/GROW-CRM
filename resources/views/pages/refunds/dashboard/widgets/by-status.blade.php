<div class="col-lg-6 col-md-12">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Refunds By Status</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th class="text-center">Count</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($by_status as $status)
                        <tr>
                            <td>
                                <span class="badge badge-pill badge-{{ $status->color ?? 'default' }}">
                                    {{ $status->title }}
                                </span>
                            </td>
                            <td class="text-center">{{ $status->count }}</td>
                            <td class="text-right">{{ runtimeMoneyFormat($status->sum) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
