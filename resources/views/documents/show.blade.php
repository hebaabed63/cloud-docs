

@extends('layouts.app')

@section('title', 'معاينة المستند')

@section('content')

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
      <li>الحجم: {{ $document->size }} كيلوبايت</li>
      <li>تاريخ الرفع: {{ $document->created_at->format('Y-m-d')}}</li>
      <li>التصنيف: {{ $document->category }}</li>
    </ul>
  </div>
</div>
@endsection


