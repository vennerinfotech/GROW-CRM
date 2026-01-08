<div class="col-lg-4  col-md-12">
    <div class="card">
        <div class="card card-body mailbox m-b-0">
            <h5 class="card-title">Lead Stages</h5>
            <div class="message-center dashboard-projects-admin">
                @foreach($payload['lead_stages_stats'] as $stat)
                <!-- {{ $stat['title'] }} -->
                <a href="{{ url('/leads?filter_lead_status='.$stat['id']) }}">
                    <div class="btn btn-info btn-circle">{{ $stat['count'] }}</div>
                    <div class="mail-contnet">
                        <h5>{{ $stat['title'] }}</h5> 
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

