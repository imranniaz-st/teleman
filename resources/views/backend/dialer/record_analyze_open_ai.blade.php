@extends('backend.layouts.master')

@section('title')
{{ translate('Audio Record Analyze Of') }} - {{ $file_name }}
@endsection

@section('css')

@endsection

@section('content')

<h6 class="ff-mono ff-base fw-medium title h6">{{ translate('Audio Transcribed') }}</h6>
<p class="ff-mono">
    {{ $transcribed_text }}
</p>

<br><br>

<h6 class="ff-mono ff-base fw-medium title h6">{{ translate('Audio Record Analyze') }}</h6>
<p class="ff-mono">
    {{ $analyze_call_record }}
</p>

@endsection

@section('js')

@endsection
