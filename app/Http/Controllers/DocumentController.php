<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;
use App\Models\Document;
use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Api\Exception\ApiError;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\Element\Text;

use DOMDocument;

class DocumentController extends Controller
{
    public function index()
    {
        return view('home');
    }
    public function showUploadForm()
    {
        return view('upload');
    }

    public function handleUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx',
        ]);
    //  بداية الوقت
    $startTime = microtime(true);

        $file = $request->file('file');

        $uploaded = Cloudinary::uploadApi()->upload($file->getRealPath(), [
            'resource_type' => 'auto',
            'folder' => 'uploads'
        ]);

        $uploadResult = $uploaded->getArrayCopy();
        $path = $uploadResult['secure_url'] ?? null;
        $publicId = $uploadResult['public_id'] ?? null;
        $size = $uploadResult['bytes'] ?? null;
        $title = '';
        $content = '';

        if ($file->getClientOriginalExtension() === 'pdf') {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($file->getPathname());
            $content = $pdf->getText();
            $title = strtok($content, "\n"); // أول سطر كعنوان
        } elseif ($file->getClientOriginalExtension() === 'docx') {
            $zip = new \ZipArchive();
            if ($zip->open($file->getPathname()) === true) {
                if (($index = $zip->locateName('word/document.xml')) !== false) {
                    $data = $zip->getFromIndex($index);
                    $zip->close();

                    $dom = new \DOMDocument();
                    $dom->loadXML($data);
                    $textNodes = $dom->getElementsByTagName('t');
                    foreach ($textNodes as $textNode) {
                        $content .= $textNode->nodeValue . ' ';
                    }

                    $lines = preg_split('/[\r\n\.]+/', $content);
                    $firstLine = isset($lines[0]) ? trim($lines[0]) : 'بدون عنوان';
                    $title = \Illuminate\Support\Str::limit($firstLine, 100);
                }
            }
        }

        $document = new Document();
        $document->filename = $file->getClientOriginalName();
        $document->title = $title ?? 'No Title';
        $document->file_path = $path;
        $document->size = $size;
        $document->public_id = $publicId;
        $document->content = $content ?? '';
        $document->category = null;



        $this->autoClassify($document);


//  نهاية الوقت
    $endTime = microtime(true);
    $duration = round($endTime - $startTime, 4); // الوقت بالثواني (دقة 4 منازل عشرية)

    return redirect()->back()->with([
        'success' => 'Document uploaded and processed successfully!',
        'duration' => "Processing Time: {$duration} seconds"
    ]);    }




    public function show($id)
    {
        $document = Document::findOrFail($id);

        return view('documents.show', compact('document'));
    }





    public function search(Request $request)

    {

        $sortOrder = $request->query('sort', 'asc'); // أو 'desc'

        $documents = Document::query()->orderBy('title', $sortOrder)->paginate(10);;



        return view('documents.search', compact('documents', 'sortOrder'));
    }
    public function highlight($id, Request $request)
{
    $start = microtime(true);

    $query = $request->input('q');
    $document = Document::findOrFail($id);

    $highlightedContent = $document->content;

    if ($query) {
        $escapedQuery = preg_quote($query, '/');
        $highlightedContent = preg_replace("/($escapedQuery)/i", '<mark>$1</mark>', $highlightedContent);
    }

    $end = microtime(true);
    $duration = round($end - $start, 4);

    return view('documents.highlight', compact('document', 'highlightedContent', 'query'))
           ->with('duration', "Highlighting Time: {$duration} seconds");
}
    public function autoClassify(Document $document)
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            foreach (json_decode($category->keywords) as $keyword) {
                if (stripos($document->content, $keyword) !== false) {
                    $document->category = $category->name;
                    $document->save();
                    return $category->name;
                }
            }
        }

        $document->category = 'غير مصنف';
        $document->save();
        return 'غير مصنف';
    }

    public function searchAll(Request $request)
{
    $start = microtime(true);

    $query = $request->input('q');
    $documents = Document::where('content', 'LIKE', '%' . $query . '%')->get();

    $end = microtime(true);
    $duration = round($end - $start, 4);

    return view('documents.search_results', compact('documents', 'query'))
           ->with('duration', "Search Time: {$duration} seconds");
}

    public function stats()
    {
        $start = microtime(true);

        $documents = Document::all();
        $documentCount = $documents->count();


        $totalSize = Document::sum('size');;
        //
        // محاكاة وقت الفرز والتصنيف (كمثال فقط)
        usleep(50000); // 50ms فرز
        usleep(80000); // 80ms تصنيف

        $end = microtime(true);
        $executionTime = round(($end - $start) * 1000, 2); // بالمللي ثانية
        $documentsPerCategory = Document::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category');
        return view('documents.stats', [
            'documentCount' => $documentCount,
            'totalSize' => $totalSize,
            'executionTime' => $executionTime,
            'documentsPerCategory' => $documentsPerCategory
        ]);
    }
}
