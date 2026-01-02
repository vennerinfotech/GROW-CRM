<div class="col-lg-6 col-md-12">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Refunds By Payment Mode</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Payment Mode</th>
                            <th class="text-center">Count</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($by_mode as $mode)
                        <tr>
                            <td>{{ $mode->title }}</td>
                            <td class="text-center">{{ $mode->count }}</td>
                            <td class="text-right">{{ runtimeMoneyFormat($mode->sum) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
