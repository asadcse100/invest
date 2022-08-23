<div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
        <a href="javascript:void(0)" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
        <div class="modal-body modal-body-md">
            <div class="nk-modal-title">
                <h4 class="title mb-3">{!! __('Complete Auto Tranfers') !!}</h4>
                <p>{!! __('Please complete the auto transfers. Amount will be transferred to the users main account if balance crosses the threshold minimum amount.') !!}</p>
            </div>
            <div class="nk-block">
                <div class="progress-wrap mt-3">
                    <div class="progress-text">
                        <div class="progress-label text-base fw-medium">
                            {{ __('Total User Accounts') }}
                        </div>
                        <div class="progress-amount"><span class="pq-count">0</span> / <span class="total">{{ $total }}</span></div>
                    </div>
                    <div class="progress progress-lg">
                        <div class="progress-bar progress-bar-striped progress-bar-animated pq-status"></div>
                    </div>
                </div>
                <ul class="align-center flex-nowrap mt-4 pb-2 pt-1">
                    <li>
                        <button type="button" class="btn btn-primary m-sync-pay" data-method="transfers">{{ __('Complete Auto Transfers') }}</button>
                    </li>
                </ul>
                <div class="divider md stretched"></div>
                <div class="notes">
                    <ul>
                        <li class="alert-note is-plain">
                            <em class="icon ni ni-help"></em>
                            <p>{{ __("The amounts will immediately transfer into the users main account.") }}</p>
                        </li>
                        <li class="alert-note is-plain text-danger">
                            <em class="icon ni ni-alert"></em>
                            <p>{{ __("Do not reload the page while your are processing as the process may take several minutes.") }}</p>
                        </li>
                    </ul>
                </div>
            </div>
            <script type="text/javascript">
                var bulkpq = { queues: @json($users), total: {{ $total }}, batch: {{ count($users) }}, url: "{{ route('admin.investment.process.transfers.complete') }}" };
            </script>
        </div>
    </div>
</div>
