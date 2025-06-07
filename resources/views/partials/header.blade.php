
    <header class="header">
        <nav class="navbar">
            <div class="logo-container">
                <a href="{{ route('documents.index') }}" class="logo">Document Cloud</a>
                <div class="cloud-icon">☁️</div>
            </div>
            <ul class="nav-links">
                <li><a href="{{ route('upload.form') }}">رفع مستند</a></li>
                <li><a href="{{ route('documents.search') }}">بحث</a></li>
                <li><a href="{{ route('documents.stats') }}">لوحة التحكم</a></li>
            </ul>
        </nav>
    </header>
