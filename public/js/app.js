// resources/js/app.js

document.addEventListener('DOMContentLoaded', () => {

  // ======= صفحة الرفع (upload.blade.php) =======
  const dropZone = document.getElementById('dropZone');
  const fileInput = document.getElementById('fileInput');
  const fileName = document.getElementById('fileName');
  const fileSize = document.getElementById('fileSize');
  const filePreview = document.getElementById('filePreview');
  const removeBtn = document.getElementById('removeFile');
  const uploadBtn = document.getElementById('uploadBtn');
  const progressContainer = document.getElementById('progressContainer');
  const progressBar = document.getElementById('progressBar');
  const progressText = document.getElementById('progressText');
  const extractTitleBtn = document.getElementById('extractTitle');
  const documentTitleInput = document.getElementById('documentTitle');

  if (dropZone && fileInput && uploadBtn) {
    // فتح اختيار الملف عند النقر على Drop Zone
    dropZone.addEventListener('click', () => fileInput.click());

    // عند اختيار ملف
    fileInput.addEventListener('change', () => {
      const file = fileInput.files[0];
      if (file) {
        fileName.textContent = file.name;
        fileSize.textContent = (file.size / 1024).toFixed(2) + ' KB';
        filePreview.style.display = 'block';
        uploadBtn.disabled = false;
      }
    });

    // إزالة الملف المختار
    removeBtn.addEventListener('click', () => {
      fileInput.value = '';
      filePreview.style.display = 'none';
      uploadBtn.disabled = true;
      documentTitleInput.value = '';
    });

    // رفع الملف (محاكاة)
    uploadBtn.addEventListener('click', () => {
      progressContainer.style.display = 'block';
      let progress = 0;
      const interval = setInterval(() => {
        progress += 10;
        progressBar.style.width = progress + '%';
        progressText.textContent = progress + '%';
        if (progress >= 100) {
          clearInterval(interval);
          alert('تم رفع المستند بنجاح!');
          progressContainer.style.display = 'none';
          progressBar.style.width = '0%';
          progressText.textContent = '0%';
          fileInput.value = '';
          filePreview.style.display = 'none';
          uploadBtn.disabled = true;
          documentTitleInput.value = '';
        }
      }, 200);
    });

    // دعم السحب والإسقاط (Drag & Drop)
    dropZone.addEventListener('dragover', (e) => {
      e.preventDefault();
      dropZone.classList.add('drag-over');
    });

    dropZone.addEventListener('dragleave', () => {
      dropZone.classList.remove('drag-over');
    });

    dropZone.addEventListener('drop', (e) => {
      e.preventDefault();
      dropZone.classList.remove('drag-over');
      const file = e.dataTransfer.files[0];
      if (file) {
        fileInput.files = e.dataTransfer.files;
        fileName.textContent = file.name;
        fileSize.textContent = (file.size / 1024).toFixed(2) + ' KB';
        filePreview.style.display = 'block';
        uploadBtn.disabled = false;
      }
    });

    // زر استخراج العنوان من الملف (محاكاة فقط)
    extractTitleBtn.addEventListener('click', () => {
      if (fileInput.files.length === 0) {
        alert('الرجاء اختيار ملف أولاً');
        return;
      }
      // هنا ممكن تضيف استدعاء API لاستخراج عنوان من محتوى الملف
      // محاكاة بعنوان ثابت:
      documentTitleInput.value = 'عنوان المستند المستخرج تلقائياً';
    });
  }

  // ======= صفحة البحث (search.blade.php) =======
  const searchInput = document.getElementById('searchInput');
  const searchBtn = document.getElementById('searchBtn');
  const resultsContainer = document.getElementById('resultsContainer');
  const sortBy = document.getElementById('sortBy');
  const filterCategory = document.getElementById('filterCategory');

  if (searchBtn && searchInput && resultsContainer) {
    const emptyStateHTML = `
      <div class="empty-state">
        <svg viewBox="0 0 24 24">
          <path d="M11 15h2v2h-2zm0-8h2v6h-2zm1-5C6.47 2 2 6.5 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2m0 18a8 8 0 0 1-8-8a8 8 0 0 1 8-8a8 8 0 0 1 8 8a8 8 0 0 1-8 8z"/>
        </svg>
        <p>لا توجد نتائج للعرض. ابدأ بالبحث الآن.</p>
      </div>`;

    function renderResults(docs) {
      if (!docs || docs.length === 0) {
        resultsContainer.innerHTML = emptyStateHTML;
        return;
      }
      const html = docs.map(doc => `
        <div class="document-card">
          <div class="document-header">
            <div class="document-title">${doc.title}</div>
            <div class="document-date">${doc.date}</div>
          </div>
          <div class="document-snippet">${doc.snippet}</div>
          <div class="document-footer">
            <div class="document-category category-${doc.category}">${doc.categoryName}</div>
            <div class="document-actions">
              <button onclick="alert('فتح المستند: ${doc.title}')">فتح</button>
              <button onclick="alert('حذف المستند: ${doc.title}')">حذف</button>
            </div>
          </div>
        </div>
      `).join('');
      resultsContainer.innerHTML = html;
    }

    // محاكاة بيانات بحث
    const fakeDocs = [
      {
        title: 'مستند تقني 1',
        date: '2025-06-01',
        snippet: 'هذا ملخص تقني للمستند الأول...',
        category: 'technology',
        categoryName: 'تقنية'
      },
      {
        title: 'مستند طبي 2',
        date: '2025-05-28',
        snippet: 'هذا ملخص طبي للمستند الثاني...',
        category: 'medical',
        categoryName: 'طبية'
      },
      {
        title: 'مستند تعليمي 3',
        date: '2025-05-15',
        snippet: 'هذا ملخص تعليمي للمستند الثالث...',
        category: 'educational',
        categoryName: 'تعليمية'
      }
    ];

    function filterAndSortDocs() {
      let filtered = fakeDocs;

      // فلترة حسب التصنيف
      const cat = filterCategory.value;
      if (cat !== 'all') {
        filtered = filtered.filter(d => d.category === cat);
      }

      // فرز حسب المحدد
      const sortVal = sortBy.value;
      if (sortVal === 'date') {
        filtered.sort((a,b) => new Date(b.date) - new Date(a.date));
      } else if (sortVal === 'name') {
        filtered.sort((a,b) => a.title.localeCompare(b.title));
      } else if (sortVal === 'size') {
        // الحجم غير متوفر، نرجع كما هو
      }

      return filtered;
    }

    searchBtn.addEventListener('click', () => {
      const query = searchInput.value.trim();
      if (!query) {
        alert('الرجاء إدخال كلمة بحث');
        return;
      }
      const results = filterAndSortDocs();
      // محاكاة فلترة النتائج بالكلمة المفتاحية
      const filteredResults = results.filter(d => d.title.includes(query) || d.snippet.includes(query));
      renderResults(filteredResults);
    });

    // تحديث النتائج عند تغيير الفلاتر بدون بحث (اختياري)
    sortBy.addEventListener('change', () => {
      if (searchInput.value.trim() === '') {
        resultsContainer.innerHTML = emptyStateHTML;
        return;
      }
      const results = filterAndSortDocs();
      renderResults(results);
    });
    filterCategory.addEventListener('change', () => {
      if (searchInput.value.trim() === '') {
        resultsContainer.innerHTML = emptyStateHTML;
        return;
      }
      const results = filterAndSortDocs();
      renderResults(results);
    });

  }

  // ======= صفحة الداشبورد (dashboard.blade.php) =======
  // مثال تفاعل مع بطاقات الداشبورد (لو كانت موجودة)
  const statsRefreshBtn = document.getElementById('refreshStats');
  if (statsRefreshBtn) {
    statsRefreshBtn.addEventListener('click', () => {
      statsRefreshBtn.disabled = true;
      statsRefreshBtn.textContent = 'جاري التحديث...';

      // محاكاة طلب API لجلب بيانات جديدة
      setTimeout(() => {
        alert('تم تحديث إحصائيات الداشبورد!');
        statsRefreshBtn.disabled = false;
        statsRefreshBtn.textContent = 'تحديث الإحصائيات';
      }, 1500);
    });
  }

  // ======= صفحة المعاينة (preview.blade.php) =======
  const printBtn = document.getElementById('printBtn');
  if (printBtn) {
    printBtn.addEventListener('click', () => {
      window.print();
    });
  }

});
