@extends('layouts.app')

@section('title', 'رفع مستند جديد')

@section('content')
 @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif


<form id="uploadForm" action="{{ route('upload.handle') }}" method="POST" enctype="multipart/form-data">
    @csrf
<div class="upload-section card">
    <h1 class="section-title">رفع مستند جديد</h1>

    <div class="upload-area">
        <div class="drop-zone" id="dropZone">
            <svg class="upload-icon" viewBox="0 0 24 24">
                <path d="M7 10v4h10v-4h2v4a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-4h2m5-3l5 5h-4v6h-2v-6H7l5-5z"/>
            </svg>
            <p>اسحب وأسقط الملفات هنا أو انقر لاختيار الملفات</p>
            <input type="file" id="fileInput" name="file" accept=".pdf,.doc,.docx">
        </div>

        <div class="file-preview" id="filePreview">
            <div class="file-info">
                <span class="file-icon">📄</span>
                <div>
                    <p class="file-name" id="fileName"></p>
                    <p class="file-size" id="fileSize"></p>
                </div>
                <button class="remove-btn" id="removeFile">×</button>
            </div>
        </div>



        <button class="btn btn-primary" id="uploadBtn"  type="submit" disabled>رفع المستند</button>

        <div class="progress-container" id="progressContainer">
            <div class="progress-bar" id="progressBar"></div>
            <span class="progress-text" id="progressText">0%</span>
        </div>
    </div>
</div>
</form>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('fileInput');
    const uploadBtn = document.getElementById('uploadBtn');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const filePreview = document.getElementById('filePreview');
    const removeBtn = document.getElementById('removeFile');

    // لما يختار ملف
    fileInput.addEventListener('change', function () {
        const file = fileInput.files[0];

        if (file) {
            uploadBtn.disabled = false;
            filePreview.style.display = 'block';
            fileName.textContent = file.name;
            fileSize.textContent = (file.size / 1024).toFixed(2) + ' KB';
        } else {
            uploadBtn.disabled = true;
            filePreview.style.display = 'none';
        }
    });

    // إزالة الملف
    removeBtn.addEventListener('click', function () {
        fileInput.value = '';
        uploadBtn.disabled = true;
        filePreview.style.display = 'none';
        fileName.textContent = '';
        fileSize.textContent = '';
    });

    // في البداية يتعطل زر الرفع
    uploadBtn.disabled = true;
});</script>
@endsection


