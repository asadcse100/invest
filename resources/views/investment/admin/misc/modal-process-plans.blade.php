<div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
        <a href="javascript:void(0)" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
        <div class="modal-body modal-body-md">
            <div class="nk-modal-title">
                <h4 class="title mb-3">{!! __('Sync Investment Plans') !!}</h4>
                <p>{!! __('Please sync all the active invested plans to calculate and adjust profits into user accounts. You can perform bulk action to calculate profits easily.') !!}</p>
            </div>
            <div class="nk-block">
                <div class="progress-wrap mt-3">
                    <div class="progress-text">
                        <div class="progress-label text-base fw-medium">
                            {{ __('Actived Invested Plans') }}
                        </div>
                        <div class="progress-amount"><span class="pq-count">0</span> / <span class="total">{{ $total }}</span></div>
                    </div>
                    <div class="progress progress-lg">
                        <div class="progress-bar progress-bar-striped progress-bar-animated pq-status"></div>
                    </div>
                </div>
                <ul class="align-center flex-nowrap mt-4 pb-2 pt-1">
                    <li>
                        <button type="button" class="btn btn-primary m-sync-pay" data-method="plans">{{ __('Sync Invested Plans') }}</button>
                    </li>
                </ul>
                <div class="divider md stretched"></div>
                <div class="notes">
                    <ul>
                        <li class="alert-note is-plain">
                            <em class="icon ni ni-help"></em>
                            <p>{{ __("All the active invested plans will check automatically and adjust pending profits.") }}</p>
                        </li>
                        <li class="alert-note is-plain">
                            <em class="icon ni ni-help"></em>
                            <p>{{ __("The profits will show as locked amount into user account once sync complete.") }}</p>
                        </li>
                        <li class="alert-note is-plain">
                            <em class="icon ni ni-help"></em>
                            <p>{{ __("You must approved the profits to release locked amount from user account.") }}</p>
                        </li>
                        <li class="alert-note is-plain text-danger">
                            <em class="icon ni ni-alert"></em>
                            <p>{{ __("Do not reload the page while your are processing as the process may take several minutes.") }}</p>
                        </li>
                    </ul>
                </div>
            </div>
            <script type="text/javascript">
                var bulkpq = { queues: @json($plans), total: {{ $total }}, batch: {{ count($plans) }}, url: "{{ route('admin.investment.process.plans.sync') }}" };
            </script>
        </div>
    </div>
</div>
