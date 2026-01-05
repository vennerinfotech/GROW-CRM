<div class="row">
    <!-- Today -->
    <div class="col-lg-4 col-md-12">
        <div class="card clearfix" id="widget-refund-today">
            <div class="card-body p-l-15 p-r-15">
                <div class="d-flex p-10 no-block">
                    <span class="align-slef-center">
                        <h2 class="m-b-0">{{ runtimeMoneyFormat($stats['today']['sum']) }}</h2>
                        <h6 class="text-muted m-b-0">Refunded Today ({{ $stats['today']['count'] }})</h6>
                    </span>
                    <div class="align-self-center display-6 ml-auto"><i class="text-success icon-Credit-Card2"></i></div>
                </div>
                <!-- Link -->
                <a href="{{ url('refunds?filter_refund_created_start=' . \Carbon\Carbon::now()->format('Y-m-d') . '&filter_refund_created_end=' . \Carbon\Carbon::now()->format('Y-m-d')) }}"
                    class="card-link-overlay"></a>
            </div>
            <div class="progress">
                <div class="progress-bar bg-success w-100 h-px-3" role="progressbar" aria-valuenow="100"
                    aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <!-- This Month -->
    <div class="col-lg-4 col-md-12">
        <div class="card clearfix" id="widget-refund-month">
            <div class="card-body p-l-15 p-r-15">
                <div class="d-flex p-10 no-block">
                    <span class="align-slef-center">
                        <h2 class="m-b-0">{{ runtimeMoneyFormat($stats['month']['sum']) }}</h2>
                        <h6 class="text-muted m-b-0">Refunded This Month ({{ $stats['month']['count'] }})</h6>
                    </span>
                    <div class="align-self-center display-6 ml-auto"><i class="text-info icon-Credit-Card2"></i></div>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar bg-info w-100 h-px-3" role="progressbar" aria-valuenow="100" aria-valuemin="0"
                    aria-valuemax="100"></div>
            </div>
            <!-- Link -->
            <a href="{{ url('refunds?filter_refund_created_start=' . \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') . '&filter_refund_created_end=' . \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')) }}"
                class="card-link-overlay"></a>
        </div>
    </div>

    <!-- All Time -->
    <div class="col-lg-4 col-md-12">
        <div class="card clearfix" id="widget-refund-all">
            <div class="card-body p-l-15 p-r-15">
                <div class="d-flex p-10 no-block">
                    <span class="align-slef-center">
                        <h2 class="m-b-0">{{ runtimeMoneyFormat($stats['all']['sum']) }}</h2>
                        <h6 class="text-muted m-b-0">Total Refunded ({{ $stats['all']['count'] }})</h6>
                    </span>
                    <div class="align-self-center display-6 ml-auto"><i class="text-warning icon-Credit-Card2"></i>
                    </div>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar bg-warning w-100 h-px-3" role="progressbar" aria-valuenow="100"
                    aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <!-- Link -->
            <a href="{{ url('refunds') }}" class="card-link-overlay"></a>
        </div>
    </div>
</div>
