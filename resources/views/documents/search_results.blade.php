@extends('layouts.app')

@section('content')
<div class="container">
    <h4>نتائج البحث عن: <mark>{{ $query }}</mark></h4>

    @if($documents->isEmpty())
        <div class="alert alert-warning">لم يتم العثور على مستندات تحتوي "<strong>{{ $query }}</strong>".</div>
    @else
        <ul class="list-group">
            @foreach($documents as $doc)
                <li class="list-group-item">
                    <strong>{{ $doc->title }}</strong>
                    <a href="{{ route('documents.highlight', ['id' => $doc->id, 'q' => $query]) }}" class="btn btn-sm btn-info float-end">عرض مع تمييز</a>
                </li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('documents.index') }}" class="btn btn-secondary mt-3">🔙 رجوع</a>
</div>
@endsection
