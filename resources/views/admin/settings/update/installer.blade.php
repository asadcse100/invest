@extends('admin.layouts.minimal')
@section('title', __('Application Update'))

@section('content')
    <div class="nk-content-body">
        <div class="content-page wide-lg m-auto">
            <div class="nk-block-head nk-block-head-lg text-center mt-md-3">
                <h4 class="title text-center pb-2">{{ __('Install Update') }}</h4>
                <p class="w-max-400px mx-auto">{{ __('We found that you have updated the core application or installed new module which is required to update database. So please install the update to migrate system before start using.') }}</p>
            </div>

            <div class="nk-block wide-xs m-auto">
                <div class="card card-bordered">
                    <div class="card-inner card-inner-lg">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible">
                                <div class="alert-text">
                                    <div class="alert-text">
                                        @foreach ($errors->all() as $error)
                                            <p>{{ $error }}</p>
                                        @endforeach
                                    </div>
                                </div>
                                <button class="close" data-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($manager->hsaPendingMigration() || $manager->isUpdateAvailable())
                            <div class="card-title">
                                <h6>{{ 'Required Following Update' }}</h6>
                            </div>
                            <table class="table table-bordered-plain table-plain fs-13px mb-n2">
                                <tbody>
                                @if($manager->hsaPendingMigration())
                                @foreach($manager->hsaPendingMigration(true) as $migration => $status)
                                    <tr>
                                        <td width="250">{{ str_replace(['2021_', '2022_', 'create_', 'add_'], '', $migration) }}</td>
                                        <td class="tb-col-end">
                                            <span class="text-info">{{ __('Migration Required') }} </span>
                                        </td>
                                        <td class="tb-col-end" width="10">
                                            <em class="ni ni-info-fill fs-12px" data-toggle="tooltip" title="{{ __("Waiting") }}"></em>
                                        </td>
                                    </tr>
                                @endforeach
                                @endif

                                @if($manager->isUpdateAvailable())
                                @foreach($manager->isUpdateAvailable(true) as $version => $file)
                                    <tr>
                                        <td width="250">{{ $version.'_update_system' }}</td>
                                        <td class="tb-col-end">
                                            <span class="text-info">{{ __('Update Required') }} </span>
                                        </td>
                                        <td class="tb-col-end" width="10">
                                            <em class="ni ni-info-fill fs-12px" data-toggle="tooltip" title="{{ __("Waiting") }}"></em>
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
                                </tbody>
                            </table>
                        @endif

                        <div class="form-group mt-5">
                            <div class="d-flex justify-between align-center">
                                <div class="action">
                                    <button class="btn btn-primary submit-settings" id="install-update">{{ __('Run Update') }}</button>
                                </div>
                            </div>
                            <div class="form-note text-danger mt-4">
                                {{ __('Please keep a backup of your database before update, otherwise you will not be able to recover in case of any kind of system error.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#install-update').on('click', function () {
                let msgs = {
                    title: "{{ __('Are you sure to install update?') }}",
                    btn: {cancel: "{{ __('Cancel') }}", confirm: "{{ __('Confirm & Update') }}"},
                    context: "{!! __("Please confirm that you already take a backup of your database and want to migrate the databasse and update.") !!}",
                    custom: "danger",
                    type: "warning"
                };
                let url = '{{ route('admin.update.install') }}';
                NioApp.Ask(msgs.title, msgs.context, msgs.btn, '', 'info').then(function (confirm) {
                    if (confirm) {
                        NioApp.Form.toAjax(url, {});
                    }
                })
            })
        });
    </script>
@endpush
