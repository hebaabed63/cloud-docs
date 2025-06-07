@extends('layouts.app')
@section('title', 'لوحة التحكم')


@section('content')

 <section class="dashboard">
        <div class="card">
            <div class="icon">📄</div>
            <h2>عدد المستندات</h2>
          <p>{{ $documentCount }}</p>
        </div>
            <div class="card">
        <div class="icon">💾</div>
            <h2> إجمالي حجم الملفات</h2>
           <p>{{ number_format($totalSize / 1024, 2) }}</p>
        </div>

 <div class="card">
     <div class="icon">⏱️ </div>
        <h2> الوقت المستغرق للمعالجة:</h2>
         <p>{{ $executionTime }} ms</p>
        </div>
 <div class="card">
     <div class="icon">📂</div>
        <h2> المستندات حسب التصنيف:</h2>
    <ul>
        @foreach($documentsPerCategory as $category => $count)
            <li>{{ $category ?? 'غير مصنف' }}: {{ $count }} مستند</li>
        @endforeach
    </ul>
        </div>
@endsection
