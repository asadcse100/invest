<div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
        <a href="javascript:void(0)" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
        <div class="modal-body modal-body-md">
            <div class="nk-modal-title">
                <h4 class="title mb-3">{!! __('Approve & Release Profits') !!}</h4>
                <p>{!! __('Please approve the all profits and release the locked amount from user account. Once you paid, user able to transfer thier funds into main account and withdraw.') !!}</p>
            </div>
            <div class="nk-block">
                <div class="progress-wrap">
                    <div class="progress-text">
                        <div class="label text-base fw-medium">
                            {{ __('Profit Amount to Approve') }}
                        </div>
                        <div class="amount text-base fw-medium">{{ money($amount, base_currency()) }}</div>
                    </div>
                    <div class="progress-text">
                        <div class="label text-base fw-medium">
                            {{ __('Total Profits Entries') }}
                        </div>
                        <div class="amount text-base fw-medium">{{ $total ?? 0 }}</div>
                    </div>
                    @if(empty($single))
                    <div class="progress-text pt-1">
                        <div class="progress-label text-base fw-medium">
                            {{ __('Total User Accounts') }}
                        </div>
                        <div class="progress-amount"><span class="pq-count">0</span> / <span class="total">{{ count($accounts) }}</span></div>
                    </div>
                    @endif
                    <div class="progress progress-lg">
                        <div class="progress-bar progress-bar-striped progress-bar-animated pq-status"></div>
                    </div>
                </div>
                <ul class="align-center flex-nowrap mt-4 pb-2 pt-1">
                    <li>
                        <button type="button" class="btn btn-primary m-sync-pay" data-method="profits">{{ __('Paid the Profits') }}</button>
                    </li>
                </ul>
                <div class="divider md stretched"></div>
                <div class="notes">
                    <ul>
                        <li class="alert-note is-plain">
                            <em class="icon ni ni-help"></em>
                            <p>{{ __("User able to transfer their funds from investment account to main account once you paid.") }}</p>
                        </li>
                        <li class="alert-note is-plain text-danger">
                            <em class="icon ni ni-alert"></em>
                            <p>{{ __("Do not reload the page while your are processing as the process may take several minutes.") }}</p>
                        </li>
                    </ul>
                </div>
            </div>
            <script type="text/javascript">
                var bulkpq = { queues: @json($accounts), total: {{ count($accounts) }}, batch: {{ count($accounts) }}, url: "{{ route('admin.investment.process.profits.payout',['iv' => $single ?? null ]) }}" };
            </script>
        </div>
    </div>
</div>
