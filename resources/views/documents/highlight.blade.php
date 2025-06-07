@extends('layouts.app')

@section('content')
<div class="container">
    <h3>{{ $document->title }}</h3>

    @if($query)
        <p>الكلمة المميزة: <mark>{{ $query }}</mark></p>
    @endif

    <div style="white-space: pre-wrap; background-color: #f1f1f1; padding: 20px; border-radius: 8px;">
        {!! $highlightedContent !!}
    </div>

    <a href="{{ route('documents.search', ['q' => $query]) }}" class="btn btn-secondary mt-3">🔙 رجوع للنتائج</a>
</div>
@endsection
