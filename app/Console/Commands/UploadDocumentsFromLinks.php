<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Document;
use Smalot\PdfParser\Parser as PdfParser;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use ZipArchive;
use DOMDocument;
use Illuminate\Support\Facades\Http;

class UploadDocumentsFromLinks extends Command
{
    protected $signature = 'documents:upload-from-links';
    protected $description = 'تحميل ورفع ملفات PDF/Word من روابط ثابتة مباشرة إلى Cloudinary وتخزينها في قاعدة البيانات';

    protected $fileLinks = [
        'https://unec.edu.az/application/uploads/2014/12/pdf-sample.pdf',
        'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
        'https://file-examples.com/storage/fe6c8ddf18a645b289953e0/2017/02/file-sample_100kB.docx',
        'https://file-examples.com/storage/fe6c8ddf18a645b289953e0/2017/02/file-sample_500kB.docx',
        'https://file-examples.com/storage/fe6c8ddf18a645b289953e0/2017/02/file-sample_1MB.docx',
        'https://file-examples.com/storage/fe6c8ddf18a645b289953e0/2017/02/file-sample_100kB.pdf',
        'https://file-examples.com/storage/fe6c8ddf18a645b289953e0/2017/02/file-sample_500kB.pdf',
        'https://file-examples.com/storage/fe6c8ddf18a645b289953e0/2017/02/file-sample_1MB.pdf',
    ];

    public function handle()
    {
        $this->info("📥 بدء تحميل ورفع الملفات...");

        foreach ($this->fileLinks as $index => $url) {
            $this->info("🔗 تحميل الملف رقم " . ($index + 1) . ": $url");

            try {
                // ارسال طلب Head أولاً للتأكد من صلاحية الرابط
                $headResponse = Http::withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
                ])->timeout(30)->head($url);

                if (!$headResponse->ok()) {
                    $this->error("❌ الرابط غير متاح، رمز الحالة: " . $headResponse->status());
                    continue;
                }

                // تحميل الملف مع هيدر ووقت انتظار أطول
                $response = Http::withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
                ])->timeout(60)->get($url);

                if (!$response->ok()) {
                    $this->error("❌ فشل تحميل الملف من الرابط: HTTP " . $response->status());
                    continue;
                }

                // إنشاء اسم مؤقت للملف
                $tempFilename = 'scraped-' . time() . '-' . ($index + 1) . '.' . $this->getExtensionFromUrl($url);
                $tempFilePath = storage_path('app/' . $tempFilename);

                file_put_contents($tempFilePath, $response->body());

                // رفع الملف من المسار المؤقت
                $this->uploadFileFromPath($tempFilePath, $tempFilename);

                // حذف الملف المؤقت
                unlink($tempFilePath);

            } catch (\Exception $e) {
                $this->error("❌ خطأ أثناء المعالجة: " . $e->getMessage());
                continue;
            }
        }

        $this->info("🎉 انتهى رفع جميع الملفات.");
    }

    protected function getExtensionFromUrl($url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        return $ext ?: 'pdf';
    }

    protected function uploadFileFromPath($filePath, $filename)
    {
        $uploaded = Cloudinary::uploadApi()->upload($filePath, [
            'resource_type' => 'auto',
            'folder' => 'uploads'
        ]);

        $uploadResult = $uploaded->getArrayCopy();
        $path = $uploadResult['secure_url'] ?? null;
        $publicId = $uploadResult['public_id'] ?? null;
        $size = $uploadResult['bytes'] ?? null;

        $content = '';
        $title = '';

        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if ($ext === 'pdf') {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($filePath);
            $content = $pdf->getText();
            $title = strtok($content, "\n") ?: 'بدون عنوان';
        } elseif (in_array($ext, ['doc', 'docx'])) {
            $zip = new ZipArchive();
            if ($zip->open($filePath) === true) {
                if (($index = $zip->locateName('word/document.xml')) !== false) {
                    $data = $zip->getFromIndex($index);
                    $zip->close();

                    $dom = new DOMDocument();
                    @$dom->loadXML($data);
                    $textNodes = $dom->getElementsByTagName('t');
                    foreach ($textNodes as $textNode) {
                        $content .= $textNode->nodeValue . ' ';
                    }

                    $lines = preg_split('/[\r\n\.]+/', $content);
                    $firstLine = isset($lines[0]) ? trim($lines[0]) : 'بدون عنوان';
                    $title = Str::limit($firstLine, 100);
                }
            } else {
                $title = 'بدون عنوان';
            }
        }

        $document = Document::create([
            'filename'  => $filename,
            'title'     => $title,
            'file_path' => $path,
            'public_id' => $publicId,
            'size'      => $size,
            'content'   => $content,
            'category'  => null,
        ]);

        // نداء التصنيف التلقائي بعد حفظ المستند
        $this->autoClassify($document);

        $this->info("✅ تم رفع وتخزين الملف \"$filename\" بنجاح! العنوان: $title");
    }

    public function autoClassify(Document $document)
    {
        $categories = \App\Models\Category::all();

        foreach ($categories as $category) {
            foreach (json_decode($category->keywords) as $keyword) {
                if (stripos($document->content, $keyword) !== false) {
                    $document->category = $category->name;
                    $document->save();
                    $this->info("📂 تم تصنيف المستند إلى: {$category->name}");
                    return;
                }
            }
        }

        $document->category = 'غير مصنف';
        $document->save();
        $this->info("📂 لم يتم العثور على تصنيف - تم تعيينه كـ: غير مصنف");
    }
}
