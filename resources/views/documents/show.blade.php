{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $document->title }}</h2>
    <p><strong>التصنيف:</strong> {{ $document->category ?? 'غير مصنف'}}</p>
    <p><strong>المحتوى:</strong></p>
    <pre style="background: #f5f5f5; padding: 15px;">{{ $document->content }}</pre>
    <a href="{{ route('documents.index') }}" class="btn btn-secondary">رجوع</a>
</div>
@endsection --}}
@extends('layouts.app')

@section('title', 'معاينة المستند')

@section('content')
{{-- <div class="preview-section container" dir="rtl">
     <div class="document-preview">

    <iframe src="{{ asset('storage/documents/' . "$document->filename") }}" width="100%" height="600" frameborder="0"></iframe>
  </div> --}}
  <div class="document-container">
  <h1>معاينة المستند</h1>
  <pre class="document-content">{{ $document->content }}</pre>
</div>
  <div class="document-preview">
  </div>

  <div class="document-info">
    <h2>معلومات المستند</h2>
    <ul>
      <li>العنوان: {{ $document->title }}</li>
      <li>الحجم: {{ filesize(storage_path("app/" . $document->file_path)) }} كيلوبايت</li>
      <li>تاريخ الرفع: {{ $document->created_at->format('Y-m-d')}}</li>
      <li>التصنيف: {{ $document->category }}</li>
    </ul>
  </div>
</div>
@endsection


