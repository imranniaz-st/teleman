@extends('backend.layouts.master')

@section('title')
{{ translate('Contacts') }}
@endsection


@section('css')

@endsection

@section('content')

<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <ul class="preview-list ">
                <li class="preview-item">
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalForm"><em
                            class="icon ni ni-users mr-2"></em>{{ translate('Add New Contact') }}</button>
                </li>
                <li class="preview-item">
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalTabs">
                        <em class="icon ni ni-file-text mr-2"></em>
                        {{ translate('Bulk Export & Import') }}
                    </button>
                </li>
                @cannot('agent')
                <li class="preview-item">
                    <a href="{{ route('dashboard.contact.export') }}"
                        class="btn btn-md btn-secondary">
                        <em class="icon ni ni-download mr-2"></em>
                        {{ translate('Quick Export') }}
                    </a>
                </li>
                @endcannot
                @if (csv_import_checker() == true)
                <li class="preview-item">
                    <span class="badge badge-md bg-outline-secondary" 
                          id="progressBar" 
                          title="{{ translate('CSV Import Progress') }}"
                          data-toggle="tooltip" data-placement="top">0%</span>
                </li>
                @endif
                <li class="preview-item d-none">
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#csv_viewer"><em
                            class="icon ni ni-file mr-2"></em>{{ translate('CSV Viewer') }}</button>
                </li>
            </ul>
        </div>
    </div><!-- .card-preview -->

    <div class="card card-preview">
        <div class="form-group">
            <div class="form-control-wrap">
                <form action="{{ route('dashboard.contact.search') }}" method="get">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="{{ translate('Search Contacts Here') }}" required
                           value="{{ request('search') }}">
                </form>
            </div>
        </div>
    </div>

    <div class="card card-preview">
        <div class="card-inner">

            <table class="nowrap nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                <thead>
                    <tr class="nk-tb-item nk-tb-head">
                        <th class="nk-tb-col"><span class="sub-text">{{ translate('NAME') }}</span></th>
                        <th class="nk-tb-col tb-col-mb"><span
                                class="sub-text">{{ translate('PHONE') }}</span></th>
                        <th class="nk-tb-col tb-col-md"><span
                                class="sub-text">{{ translate('COUNTRY') }}</span></th>
                        <th class="nk-tb-col tb-col-lg"><span
                                class="sub-text">{{ translate('OTHERS') }}</span></th>
                        <th class="nk-tb-col nk-tb-col-tools text-right">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts ?? allContacts() as $contact)
                        <tr class="nk-tb-item">
                            <td class="nk-tb-col">
                                <div class="user-card">
                                    <div class="user-info">
                                        <span class="tb-lead">{{ $contact->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="nk-tb-col tb-col-mb" data-order="{{ $contact->phone }}">
                                <span class="tb-amount">{{ $contact->phone }}</span>
                            </td>
                            <td class="nk-tb-col tb-col-md">
                                <span class="tb-lead">{{ $contact->country != null ? Str::upper($contact->country) : '--' }}</span>
                            </td>
                            <td class="nk-tb-col tb-col-lg">
                                <ul>
                                    <li><span class="tb-lead">{{ $contact->gender != null ? $contact->gender : '--' }}</span></li>
                                    <li><span class="tb-lead">{{ $contact->dob != null ? $contact->dob : '--' }}</span></li>
                                    <li><span class="tb-lead">{{ $contact->profession != null ? $contact->profession : '--' }}</span></li>
                                    <li><span class="tb-lead">{{ $contact->reference != null ? $contact->reference : '--' }}</span></li> 
                                </ul>
                            </td>

                            <td class="nk-tb-col nk-tb-col-tools">
                                <ul class="nk-tb-actions gx-1">
                                    <li>
                                        <div class="drodown">
                                            <a href="javascript:;" class="dropdown-toggle btn btn-icon btn-trigger"
                                                data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <ul class="link-list-opt no-bdr">
                                                    <li>
                                                        <a
                                                            href="{{ route('dashboard.contact.show', [$contact->id, Str::slug($contact->name)]) }}">
                                                            <em class="icon ni ni-pen"></em>
                                                            <span>{{ translate('Edit') }}</span>
                                                        </a>
                                                    </li>
                                                    @cannot('agent')
                                                    <li>
                                                        <a
                                                            href="{{ route('dashboard.contact.delete', [$contact->id, Str::slug($contact->name)]) }}">
                                                            <em class="icon ni ni-trash"></em>
                                                            <span>{{ translate('Remove') }}</span>
                                                        </a>
                                                    </li>
                                                    @endcannot
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </td>
                        </tr><!-- .nk-tb-item  -->
                    @empty

                    @endforelse
                </tbody>
            </table>

            @if (count(allContacts()) > 0)
            <nav>
                <ul class="pagination justify-content-center">
                    {{ allContacts()->links() }}
                </ul>
            </nav>
            @endif

        </div>
    </div>
    <!-- .card-preview -->

</div>
<!-- END: Large Slide Over Toggle -->

<!-- Modal Form -->
@includeWhen(true, 'backend.contacts.create')

<!-- Modal Form -->
<div class="modal fade" tabindex="-1" id="csv_viewer">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ translate('Upload CSV To View') }}</h4>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body modal-body-lg">
                <div class="form-control-wrap">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="input-file" accept=".csv">
                        <label class="custom-file-label"
                            for="customFile">{{ translate('Choose file') }}</label>
                    </div>
                </div>
                <div class="container-fluid mt-2">
                    <div id="handsontable-container" style="overflow: hidden; height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Export Import -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalTabs">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <a href="javascript:;" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-md">
                <h4 class="title">{{ translate('Export & Import Contacts') }}</h4>
                <ul class="nk-nav nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab"
                            href="#tabItem1">{{ translate('Import Contacts') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab"
                            href="#tabItem2">{{ translate('Export Contacts') }}</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tabItem1">
                        <form action="{{ route('dashboard.contact.import') }}"
                            class="form-validate is-alter" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label class="form-label"
                                            for="customFile">{{ translate('Contacts CSV File') }}</label>
                                        <span
                                            class="form-note">{{ translate('Specify the csv file') }}.</span>
                                    </div>
                                </div>

                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <div class="form-control-wrap">
                                            <div class="custom-file">
                                                <input type="file" single="" class="custom-file-input" id="customFile"
                                                    name="csv">
                                                <label class="custom-file-label"
                                                    for="customFile">{{ translate('Choose file') }}</label>
                                            </div>
                                            <small>{{ translate('only .csv file is applicable') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-lg-7 offset-lg-5">
                                    <div class="form-group mt-2">
                                        <button type="submit" class="btn btn-md btn-secondary"><em
                                                class="icon ni ni-upload mr-2"></em>{{ translate('Upload') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="tabItem2">
                        <a href="{{ route('dashboard.contact.export') }}"
                            class="btn btn-round btn-md btn-secondary">
                            <em class="icon ni ni-download mr-2"></em>
                            {{ translate('Export Contacts As CSV File Format') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- .modal -->

<input type="hidden" value="{{ route('dashboard.contact.import.status') }}" id="import_status">

@endsection

@section('js')
<script>
    "use strict";

    var csvImportActive = @json(csv_import_checker());

    function updateProgress() {
        if (!csvImportActive) {
            return;
        }

        $.ajax({
            url: $('#import_status').val(),
            type: 'GET',
            success: function(response) {
                $('#progressBar').text(response + '%');
            },
            complete: function() {
                setTimeout(updateProgress, 3000);
            }
        });
    }

    if (csvImportActive) {
        updateProgress();
    }
</script>
@endsection
