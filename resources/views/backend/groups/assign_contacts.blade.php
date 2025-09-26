@extends('backend.layouts.master')

@section('title')
    {{ translate('Assign Contacts') }} ⇢ {{ $group->name }}
@endsection

@section('css')
    
@endsection
    
@section('content')

<div class="nk-block nk-block-lg">

    <div class="card card-preview">
        <div class="card-inner">
            <ul class="preview-list ">
                <li class="preview-item">
                    <a href="{{ route('dashboard.assign.all.contacts', [$group->id, Str::slug($group->name)]) }}" class="btn btn-secondary">{{ translate('Assign All Contacts To Group') }}</a>
                </li>
                <li class="preview-item">
                    <a href="{{ route('dashboard.assign.remove.contacts', [$group->id, Str::slug($group->name)]) }}" class="btn btn-secondary">{{ translate('Remove All Contacts From Group') }}</a>
                </li>
            </ul>
        </div>
    </div>
  
    <div class="card card-preview">
        <div class="card-inner">
            <form action="{{ route('dashboard.contact.group.assign.store', [$group->id, Str::slug($group->name)]) }}" method="POST">
                @csrf
                <table class="contacts-datatable-init nowrap nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                    <thead>
                        <tr class="nk-tb-item nk-tb-head">
                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('CHECK') }}</span></th>
                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('NAME') }}</span></th>
                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('NUMBER') }}</span></th>
                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('COUNTRY') }}</span></th>
                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('GENDER') }}</span></th>
                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('DOB') }}</span></th>
                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('PROFESSION') }}</span></th>
                            <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('STATUS') }}</span></th>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse (allContacts() as $contact)
                        <tr class="nk-tb-item">
                            <td class="nk-tb-col nk-tb-col-check">
                                <div class="custom-control custom-control-sm custom-checkbox notext">
                                    <input type="checkbox" 
                                            class="custom-control-input" 
                                            value="{{ $contact->id }}" 
                                            id="uid{{ $contact->id }}" 
                                            name="contact_ids[]"
                                            {{ checkContactInGroup($contact->id, $group->id) == true ? 'checked' : null }}>
                                    <label class="custom-control-label" for="uid{{ $contact->id }}"></label>
                                </div>
                            </td>
                            <td class="nk-tb-col tb-col-md">
                                {{ $contact->name }}
                        </td>
                    
                        <td class="nk-tb-col tb-col-md">
                                {{ $contact->phone }}
                        </td>
                    
                        <td class="nk-tb-col tb-col-md">
                                {{ $contact->country }}
                        </td>
                    
                        <td class="nk-tb-col tb-col-md">
                                {{ $contact->gender }}
                        </td>
                    
                        <td class="nk-tb-col tb-col-md">
                                {{ $contact->dob }}
                        </td>
                    
                        <td class="nk-tb-col tb-col-md">
                                {{ $contact->profession }}
                        </td>
                        
                        <td class="nk-tb-col nk-tb-col-tools">
                            <ul class="nk-tb-actions gx-1">
                                <li>
                                    <div class="drodown">
                                        <a href="javascript:;" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <ul class="link-list-opt no-bdr">
                                                <li><a href="{{ route('dashboard.contact.show', [$contact->id, Str::slug($contact->name)]) }}"><em class="icon ni ni-pen"></em><span>{{ translate('Edit') }}</span></a></li>
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

                <div class="row g-3">
                    <div class="col-lg-12">
                        <div class="form-group mt-2">
                            <button type="submit" class="btn btn-lg btn-secondary">{{ translate('Save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div><!-- .card-preview -->
</div>
    
@endsection

@section('js')
    
@endsection


