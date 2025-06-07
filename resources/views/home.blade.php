@extends('layouts.app')

@section('title', 'الصفحة الرئيسية')

@section('content')
<div class="home-section" style="text-align: center; margin: 50px auto; max-width: 600px;">
    <h1>مرحبًا بك في نظام Document Cloud</h1>
    <p>يمكنك رفع، البحث، وإدارة مستنداتك بكل سهولة.</p>
    <div style="margin-top: 30px;">
        <a href="{{ route('upload.form') }}" class="btn btn-primary" style="padding: 12px 25px; font-size: 18px; border-radius: 6px;">
            ابدأ برفع مستند
        </a>
    </div>
</div>
@endsection
