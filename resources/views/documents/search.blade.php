
@extends('layouts.app')

@section('title', 'بحث في المستندات')

@section('content')
<div class="search-section card">
    <h1 class="section-title">بحث في المستندات</h1>

    <form method="GET" action="{{ route('documents.searchAll') }}">

    <div class="search-box">
        <input type="text" name="q" placeholder="ابحث في المستندات..." id="searchInput">
        <button class="btn btn-primary" id="searchBtn" type="submit">بحث</button>
    </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>العنوان</th>
                <th>التصنيف</th>
                <th>تاريخ الإضافة</th>
                <th>عرض</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documents as $doc)
            <tr>
                <td>{{ $doc->title }}</td>
                <td>{{ $doc->category ?? 'غير مصنف' }}</td>
                <td>{{optional($doc->created_at)->format('Y-m-d') ?? 'غير متوفر' }}</td>
                <td><a href="{{ route('documents.show', $doc->id) }}" class="btn btn-sm btn-info">عرض</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div>
      <a href="{{ route('documents.search', ['sort' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}" class="btn btn-sm btn-primary mb-3">
        ترتيب {{ $sortOrder === 'asc' ? 'تنازلي' : 'تصاعدي' }}
    </a>
    </div>
    
    </div>


</div>

<script>
// الأكواد JavaScript ستكون هنا
</script>
@endsection
