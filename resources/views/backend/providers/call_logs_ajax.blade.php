<table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
            <thead>
                <tr class="nk-tb-item nk-tb-head">
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('LOG SID') }}</span>
        </th>
        <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('FROM') }}</span></th>
        <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('TO') }}</span></th>
        <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('PRICE') }}</span></th>
        <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('RECORDING') }}</span></th>
        <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('STATUS') }}</span></th>
        <th class="nk-tb-col tb-col-xl"><span class="sub-text"></span></th>
        </th>
        </tr>
        </thead>
        <tbody> 
            @forelse($calls as $call)
                <tr class="nk-tb-item">
                    <td class="nk-tb-col">
                        <div class="user-card">
                            <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                                <span>{{ $loop->iteration }}</span>
                            </div>
                            <div class="user-info">
                                <a
                                    href="{{ route('dashboard.provider.single.call.log', [$call->sid, $account_sid]) }}">
                                    <span class="tb-lead">{{ $call->sid }} <span
                                            class="dot dot-success d-md-none ml-1"></span></span>
                                </a>
                            </div>
                        </div>
                    </td>



                    <td class="nk-tb-col tb-col-md">
                        <span class="tb-status">
                            {{ $call->from }}
                        </span>
                    </td>


                    <td class="nk-tb-col tb-col-md">
                        <span class="tb-status">
                            {{ $call->to }}
                        </span>
                    </td>

                    <td class="nk-tb-col tb-col-md">
                        <span class="tb-status">
                            {{ $call->price }}
                        </span>
                    </td>

                    <td class="nk-tb-col tb-col-md text-center">
                        <a href="{{ route('dashboard.provider.download_recording', [$call->sid, $account_sid]) }}"
                            target="_blank">
                            <em class="icon ni ni-play-fill"></em>{{ translate('Play') }}
                        </a>
                    </td>

                    <td class="nk-tb-col tb-col-md">
                        <span
                            class="tb-status text-{{ $call->status == 'completed' ? 'success' : 'danger' }}">
                            {{ $call->status == 'completed' ? 'completed' : 'queued' }}
                        </span>
                    </td>

                    <td class="nk-tb-col nk-tb-col-tools">
                        <ul class="nk-tb-actions gx-1">
                            <li>
                                <div class="drodown">
                                    <a href="javascript:;" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em
                                            class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            <li>
                                                <a href="{{ route('dashboard.provider.single.call.log', [$call->sid, $account_sid]) }}">
                                                    <em class="icon ni ni-eye-fill"></em>
                                                    <span>{{ translate('Details') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </td>
                </tr>
            @empty

            @endforelse

        </tbody>
        </table>

        <script src="{{ asset('backend/js/loader.js') }}"></script>