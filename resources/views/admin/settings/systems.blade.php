@extends('admin.layouts.master')
@section('title', __('System Status'))

@section('has-content-sidebar', 'has-content-sidebar')

@section('content-sidebar')
    @include('admin.settings.content-sidebar')
@endsection

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">{{ 'System Status' }}</h3>
                    <p>{{ 'Useful system information about the application.' }}</p>
                </div>
                <div class="nk-block-head-content">
                    <ul class="nk-block-tools gx-1">
                        <li class="d-lg-none">
                            <a href="#" class="btn btn-icon btn-trigger toggle" data-target="pageSidebar"><em class="icon ni ni-menu-right"></em></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="nk-block">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title">
                        <h6>{{ 'Application Environment' }}</h6>
                    </div>
                    <table class="table table-bordered-plain table-plain fs-13px mb-n2">
                        <tbody>
                        <tr>
                            <td width="250">{{ 'Site/App Name' }}</td>
                            <td width="24" class="text-center">
                                <em class="ni ni-help fs-12px" data-toggle="tooltip" title="Application installed in this URL"></em>
                            </td>
                            <td>{{ site_info('name') }}</td>
                        </tr>
                        <tr>
                            <td width="250">{{ 'Site Main URL' }}</td>
                            <td width="24" class="text-center">
                                <em class="ni ni-help fs-12px" data-toggle="tooltip" title="Application installed in this URL"></em>
                            </td>
                            <td>{{ site_info('url') }}</td>
                        </tr>
                        <tr>
                            <td width="250">{{ 'Site App URL' }}</td>
                            <td width="24" class="text-center">
                                <em class="ni ni-help fs-12px" data-toggle="tooltip" title="The main URL of your application set in .env file."></em>
                            </td>
                            <td>{{ site_info('url_app') }} {!! (site_info('url_app')!=site_info('url') ? '<em class="ml-1 ni ni-alert-fill fs-12px text-danger" data-toggle="tooltip" title="URL does not match. Site app url should be match with site main url."></em>' : '') !!}</td>
                        </tr>
                        <tr>
                            <td width="250">{{ 'Site App Mode' }}</td>
                            <td width="24" class="text-center">
                                <em class="ni ni-help fs-12px" data-toggle="tooltip" title="Displays environment mode of Application."></em>
                            </td>
                            <td>{{ (env('APP_ENV')) ? ucfirst(env('APP_ENV')) : '-' }}</td>
                        </tr>
                        <tr>
                            <td width="250">{{ 'Debug Mode' }}</td>
                            <td width="24" class="text-center">
                                <em class="ni ni-help fs-12px" data-toggle="tooltip" title="Displays whether or not Application is in Debug Mode."></em>
                            </td>
                            <td>{!! (env('APP_DEBUG')==true) ? '<span class="text-danger">Enable</span>' : 'Disable' !!}</td>
                        </tr>
                        <tr>
                            <td width="250">{{ 'HTTPS Connection' }}</td>
                            <td width="24" class="text-center">
                                <em class="ni ni-help fs-12px" data-toggle="tooltip" title="Is the connection to your application is secure?"></em>
                            </td>
                            <td>{!! (is_secure()) ? 'Yes' : '<span class="text-danger">Your site is not using HTTPS</span>' !!}</td>
                        </tr>
                        <tr>
                            <td width="250">{{ 'Force SSL (HTTPS)' }}</td>
                            <td width="24" class="text-center">
                                <em class="ni ni-help fs-12px" data-toggle="tooltip" title="Force to use https or not, specify in .env file."></em>
                            </td>
                            <td>{!! (is_force_https()) ? '<span class="text-danger">Enable</span>' : 'Disable' !!}</td>
                        </tr>
                        <tr>
                            <td width="250">{{ 'Default Upload Directory' }}</td>
                            <td width="24" class="text-center">
                                <em class="ni ni-help fs-12px" data-toggle="tooltip" title="The system path of your KYC document upload."></em>
                            </td>
                            <td>
                                <code>{{ (is_demo()) ? str_replace(storage_path(), '', storage_path('app/public')) : storage_path('app/public') }}</code>
                            </td>
                        </tr>
                        <tr>
                            <td width="250">{{ 'Log Directory' }}</td>
                            <td width="24" class="text-center">
                                <em class="ni ni-help fs-12px" data-toggle="tooltip" title="The system path of your KYC document upload."></em>
                            </td>
                            <td>
                                <code>{{ (is_demo()) ? str_replace(storage_path(), '', storage_path('logs')) : storage_path('logs') }}</code>
                            </td>
                        </tr>
                        <tr>
                            <td width="250">{{ 'Cache Directory' }}</td>
                            <td width="24" class="text-center">
                                <em class="ni ni-help fs-12px" data-toggle="tooltip" title="The system path of your KYC document upload."></em>
                            </td>
                            <td>
                                <code>{{ (is_demo()) ? str_replace(storage_path(), '', storage_path('framework')) : storage_path('framework') }}</code>
                            </td>
                        </tr>
                        @if(!is_demo())
                        <tr>
                            <td width="250">{{ 'Application Status' }}</td>
                            <td width="24" class="text-center">
                                <em class="ni ni-help fs-12px" data-toggle="tooltip" title="Status of the application."></em>
                            </td>
                            <td>{!! (sys_info('lk'.'ey') && gas()) ? 'Active <a class="ml-2 system-' . 'opt" data-action="system" href="javascript:void(0)">Revoke</a>' : '<a href="'.route('admi'.'n.qu'.'ick.re'.'gist'.'er').'"><span class="text-soft">No' .'t a' . 'cti' . 've yet</span> - Cli' .'ck he' . 're to ac' .  'ti'  . 've</a>' !!}</td>
                        </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>{{-- .card --}}

            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title">
                        <h6>{{ 'Modules and System' }}</h6>
                    </div>
                    <table class="table table-bordered-plain table-plain fs-13px mb-n2">
                        <tbody>
                            <tr>
                                <td width="250">{{ 'Core Application / System' }}</td>
                                <td width="24" class="text-center">
                                    <em class="ni ni-help fs-12px" data-toggle="tooltip" title="The installed version of the application."></em>
                                </td>
                                <td>{{ 'Base / App v'.config('app.version') }} <code class="code text-soft">/{{ config('app.build') }}</code></td>
                            </tr>
                            <tr>
                                <td width="250">{{ 'Core Application / Type' }}</td>
                                <td width="24" class="text-center">
                                    <em class="ni ni-help fs-12px" data-toggle="tooltip" title="The running application type."></em>
                                </td>
                                <td>
                                    {!! (sys_info('pty'.'pe') && gas()) ? (starts_with(sys_info('pi'.'n'), 'l1') ? '<span class="text-base">Reg'. 'ular</span>' : '<span class="text-purple">Ext'.'ended</span>') : '<span class="text-soft">Un' . 'kno' .'wn</span>' !!}
                                    {!! sys_info('pc'.'ode') ? '/ '.str_compact(sys_info('pco'.'de')).( !gas() ? ' <a class="ml-1" href="'.route('adm'.'in.qu'.'ick.regi'.'ster').'">Ent'. 'er co'. 'rr' .'ect cod' . 'e</a>' : '' ) : ' <a class="ml-1" href="'.route('ad'.'min.q'.'uic'.'k.reg'.'ister').'">En'. 'ter yo' .'ur pu' . 'rch'. 'ase co'. 'de</a>' !!}
                                </td>
                            </tr>
                            @if(!empty(get_module_addons()))
                            @foreach (get_module_addons() as $module)
                            @if(data_get($module, 'name'))
                            <tr>
                                <td width="250">
                                    {{ __(data_get($module, 'name')) }}
                                    @if(data_get($module, 'system.kind'))
                                    / {{ data_get($module, 'system.kind') }}
                                    @endif
                                </td>
                                <td width="24" class="text-center">
                                    <em class="ni ni-help fs-12px" data-toggle="tooltip" title="{{ data_get($module, 'system.description') }}"></em>
                                </td>
                                <td>
                                    {{ data_get($module, 'system.info') }}
                                    @if(data_get($module, 'system.version'))
                                    <code class="ml-2{{ (data_get($module, 'system.addons', false)==false) ? ' text-primary' : ''}}">{{  ucfirst(data_get($module, 'system.type')).'/v'.data_get($module, 'system.version') }}</code>
                                    @endif
                                </td>
                            </tr>
                            @endif
                            @endforeach
                            @endif

                            @if(!empty(get_additional_addons()))
                            @foreach (get_additional_addons() as $module)
                            @if(data_get($module, 'name'))
                            <tr>
                                <td width="250">
                                    {{ __(data_get($module, 'name')) }}
                                    @if(data_get($module, 'system.kind'))
                                    / {{ data_get($module, 'system.kind') }}
                                    @endif
                                </td>
                                <td width="24" class="text-center">
                                    <em class="ni ni-help fs-12px" data-toggle="tooltip" title="{{ data_get($module, 'system.description') }}"></em>
                                </td>
                                <td>
                                    {{ data_get($module, 'system.info') }}
                                    @if(data_get($module, 'system.version'))
                                    <code class="ml-2{{ (data_get($module, 'system.addons', false)==false) ? ' text-primary' : ''}}">{{  ucfirst(data_get($module, 'system.type')).'/v'.data_get($module, 'system.version') }}</code>
                                    @endif
                                </td>
                            </tr>
                            @endif
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>{{-- .card --}}

            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title">
                        <h6>{{ 'Server Environment' }}</h6>
                    </div>
                    <table class="table table-bordered-plain table-plain fs-13px mb-n2">
                        <tbody>
                            <tr>
                                <td width="250">{{ 'Server Info' }}</td>
                                <td width="24" class="text-center">
                                    <em class="ni ni-help fs-12px" data-toggle="tooltip" title="The running server software on your hosting."></em>
                                </td>
                                <td>{{ request()->server('SERVER_SOFTWARE') }}</td>
                            </tr>
                            <tr>
                                <td width="250">{{ 'Server Timezone' }}</td>
                                <td width="24" class="text-center">
                                    <em class="ni ni-help fs-12px" data-toggle="tooltip" data-placement="top" title="The timezone of server."></em>
                                </td>
                                <td>{{ date_default_timezone_get() }}<em class="ml-1 ni ni-info-i fs-12px text-soft" data-toggle="tooltip" title="System: {{ gss('time_zone') }}"></em></td>
                            </tr>
                            <tr>
                                <td width="250">{{ 'PHP Version' }}</td>
                                <td width="24" class="text-center">
                                    <em class="ni ni-help fs-12px" data-toggle="tooltip" title="The version of PHP installed on your hosting server."></em>
                                </td>
                                <td>
                                    {!! phpversion() !!}
                                </td>
                            </tr>
                            <tr>
                                <td width="250">{{ 'cURL version' }}</td>
                                <td width="24" class="text-center">
                                    <em class="ni ni-help fs-12px" data-toggle="tooltip" title="The version of cURL on your server."></em>
                                </td>
                                <td>
                                    {!! (!empty(curl_version()) ? curl_version()['version'].', '.curl_version()['ssl_version'] : '-')  !!}
                                </td>
                            </tr>
                            <tr>
                                <td width="250">{{ 'OpenSSL' }}</td>
                                <td width="24" class="text-center">
                                    <em class="ni ni-help fs-12px" data-toggle="tooltip" title="The version of cURL on your server."></em>
                                </td>
                                <td>
                                    {{ OPENSSL_VERSION_TEXT.' | '.OPENSSL_VERSION_NUMBER }}
                                </td>
                            </tr>
                            <tr>
                                <td width="250">{{ 'MySQL Version' }}</td>
                                <td width="24" class="text-center">
                                    <em class="ni ni-help fs-12px" data-toggle="tooltip" title="The version of MySQL installed on your hosting server."></em>
                                </td>
                                <td>
                                    @php
                                    $results = DB::select( DB::raw("select version()") );
                                    $mysql_version = isset($results[0]->{'version()'}) ? $results[0]->{'version()'} : '*.*.*';
                                    @endphp
                                    {{ $mysql_version }}
                                </td>
                            </tr>
                            <tr>
                                <td width="250">{{ 'PHP Post Max Size' }}</td>
                                <td width="24" class="text-center">
                                    <em class="ni ni-help fs-12px" data-toggle="tooltip" title="The largest file size that can be contained in one post."></em>
                                </td>
                                <td>
                                    {{ ini_get('post_max_size').'B' }} {!! ((int)ini_get('post_max_size') < 32 ? '<em class="ml-1 ni ni-alert-fill fs-12px text-info" data-toggle="tooltip" title="Recommend is 32MB or above."></em>' : '') !!}
                                </td>
                            </tr>
                            <tr>
                                <td width="250">{{ 'Max Upload Size' }}</td>
                                <td width="24" class="text-center">
                                    <em class="ni ni-help fs-12px" data-toggle="tooltip" title="The largest file size that can be contained in one post."></em>
                                </td>
                                <td>
                                    {{ ini_get('upload_max_filesize').'B' }} {!! ((int)ini_get('upload_max_filesize') < 8 ? '<em class="ml-1 ni ni-alert-fill fs-12px text-info" data-toggle="tooltip" title="Recommend is 8MB or above."></em>' : '') !!}
                                </td>
                            </tr>
                            <tr>
                                <td width="250">{{ 'PHP Memory Limit' }}</td>
                                <td width="24" class="text-center">
                                    <em class="ni ni-help fs-12px" data-toggle="tooltip" title="The maximum amount of memory (RAM) that your site can use at one time."></em>
                                </td>
                                <td>
                                    {{ ini_get('memory_limit').'B' }} {!! ((int)ini_get('memory_limit') < 256 ? '<em class="ml-1 ni ni-alert-fill fs-12px text-info" data-toggle="tooltip" title="Recommend is 256MB or above."></em>' : '') !!}
                                </td>
                            </tr>
                            <tr>
                                <td width="250">{{ 'PHP Time Limit' }}</td>
                                <td width="24" class="text-center">
                                    <em class="ni ni-help fs-12px" data-toggle="tooltip" title="The amount of time (in seconds) that your site will spend on a single operation before timing out (to avoid server lockups)"></em>
                                </td>
                                <td>
                                    {{ ini_get('max_execution_time') }} {!! ((int)ini_get('max_execution_time') < 300 ? '<em class="ml-1 ni ni-alert-fill fs-12px text-info" data-toggle="tooltip" title="Recommend is 300 or above."></em>' : '') !!}
                                </td>
                            </tr>
                            <tr>
                                <td width="250">{{ 'PHP Max Input Vars' }}</td>
                                <td width="24" class="text-center">
                                    <em class="ni ni-help fs-12px" data-toggle="tooltip" title="The maximum number of variables your server can use for a single function to avoid overloads."></em>
                                </td>
                                <td>
                                    {{ ini_get('max_input_vars') }} {!! ((int)ini_get('max_input_vars') < 1500 ? '<em class="ml-1 ni ni-alert-fill fs-12px text-light" data-toggle="tooltip" title="Recommend is 1500 or above."></em>' : '') !!}
                                </td>
                            </tr>
                            <tr>
                                <td width="250">{{ 'ionCube Loader' }}</td>
                                <td width="24" class="text-center">
                                    <em class="ni ni-help fs-12px" data-toggle="tooltip" title="ionCube loader version 10.4+ is require to run any additional module and addon."></em>
                                </td>
                                <td>
                                    {{ (has_ioncube() ? 'Enabled / ' . has_ioncube(true) : 'Disabled') }} {!! (!(has_ioncube('fileinfo')) ? '<em class="ml-1 ni ni-alert-fill fs-12px text-danger" data-toggle="tooltip" title="Missing php ionCube loader extension."></em>' : '') !!}
                                </td>
                            </tr>
                            <tr>
                                <td width="250">{{ 'Fileinfo Extension' }}</td>
                                <td width="24" class="text-center">
                                    <em class="ni ni-help fs-12px" data-toggle="tooltip" title="PHP extension status"></em>
                                </td>
                                <td>
                                    {{ (extension_loaded('fileinfo') ? 'Enabled' : 'Disabled') }} {!! (!(extension_loaded('fileinfo')) ? '<em class="ml-1 ni ni-alert-fill fs-12px text-danger" data-toggle="tooltip" title="Missing php fileinfo extension."></em>' : '') !!}
                                </td>
                            </tr>
                            <tr>
                                <td width="250">{{ 'Mbstring Extension' }}</td>
                                <td width="24" class="text-center">
                                    <em class="ni ni-help fs-12px" data-toggle="tooltip" title="PHP extension status"></em>
                                </td>
                                <td>
                                    {{ (extension_loaded('mbstring') ? 'Enabled' : 'Disabled') }} {!! (!(extension_loaded('mbstring')) ? '<em class="ml-1 ni ni-alert-fill fs-12px text-danger" data-toggle="tooltip" title="Missing php mbstring extension."></em>' : '') !!}
                                </td>
                            </tr>
                            <tr>
                                <td width="250">{{ 'XML Extension' }}</td>
                                <td width="24" class="text-center">
                                    <em class="ni ni-help fs-12px" data-toggle="tooltip" title="PHP extension status"></em>
                                </td>
                                <td>
                                    {{ (extension_loaded('xml') ? 'Enabled' : 'Disabled') }} {!! (!(extension_loaded('xml')) ? '<em class="ml-1 ni ni-alert-fill fs-12px text-danger" data-toggle="tooltip" title="Missing php xml extension."></em>' : '') !!}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    const routes = { system: "{{ route('admin.quick.register', ['state' => 'revoke']) }}" };
</script>
@endpush
