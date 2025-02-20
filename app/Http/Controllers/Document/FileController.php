<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;



class FileController extends Controller
{
    public function uploadAndConvert(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'file' => 'required|mimes:doc,docx,pdf|max:5120',
            'subject' => 'required|max:256|string',
            'title_file' => 'required|max:256|string',
        ]);

        // Kiểm tra xem có file được gửi lên không
        if (!$request->hasFile('file')) {
            return response()->json(['message' => 'No file uploaded'], 400);
        }

        try {
            $uploadedFile = $request->file('file');
            if (!$uploadedFile->isValid()) {
                return response()->json(['message' => 'Invalid file'], 400);
            }

            // Đặt tên file
            $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
            $fileExtension = $uploadedFile->getClientOriginalExtension();

            // Nếu là PDF thì chỉ cần lưu và lưu vào DB
            if ($fileExtension === 'pdf') {
                $filePath = $uploadedFile->storeAs('uploads', $fileName, 'public');
                if (!$filePath) {
                    return response()->json(['message' => 'File upload failed'], 500);
                }

                // Gọi hàm lưu vào DB
                $file = $this->saveFileToDatabase($request, $fileName, $filePath);

                if ($file) {
                    $this->getPdfPreview($filePath, $file->Dcm_id);
                }
                
                return response()->json([
                    'message' => 'File uploaded successfully!',
                    'file' => $file,
                ], 200);
            }

            // Nếu là doc hoặc docx thì chuyển sang PDF
            $pdfPath = $this->convertToPdf($uploadedFile);

            // Gọi hàm lưu vào DB
            $file = $this->saveFileToDatabase($request, $uploadedFile->getClientOriginalName(), $pdfPath);

            //tạo preview cho file pdf
            if ($file) {
                $this->getPdfPreview($pdfPath, $file->Dcm_id);
            }

            return response()->json([
                'message' => 'File uploaded and converted to PDF successfully!',
                'file' => $file,
                'pdf_url' => asset('storage/' . $pdfPath)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while processing the file.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Convert DOC/DOCX to PDF
     */
    private function convertToPdf($uploadedFile)
    {
        // Tải nội dung của DOCX bằng PhpWord
        $phpWord = IOFactory::load($uploadedFile->getPathname());

        // Chuyển đổi nội dung DOCX thành HTML
        $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
        ob_start();
        $htmlWriter->save('php://output');
        $htmlContent = ob_get_clean();
        $htmlContent = preg_replace('/font-family:[^;"]+;?/', '', $htmlContent);

        $data = [
            'title' => pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME),
            'content' => $htmlContent
        ];

        // Tạo PDF từ Blade Template với mPDF và nhúng font tiếng Việt
        $pdf = PDF::loadView('pdf.file', $data)->setPaper('a4')->setOptions([
            'defaultFont' => 'DejaVuSans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);

        $pdfFileName = time() . '_' . pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME) . '.pdf';
        $pdfPath = 'uploads/' . $pdfFileName;

        // Lưu file PDF vào storage/public
        Storage::disk('public')->put($pdfPath, $pdf->output());

        return $pdfPath;
    }

    /**
     * Save file information to Database
     */
    private function saveFileToDatabase($request, $description, $filePath)
    {
        $file = new Document();
        $file->dcm_description = $description;
        $file->dcm_title = $request->input('title_file');
        $file->subject = $request->input('subject');
        $file->dcm_file_path = $filePath;
        $file->us_id = Auth::id();

        if (!$file->save()) {
            throw new \Exception('Database insert failed');
        }

        return $file;
    }


    /**
     * Trích xuất trang đầu tiên của file PDF.
     */
    private function getPdfPreview($filePath, $id)
    {
        try {
            // Đảm bảo đường dẫn đầy đủ của file PDF
            $pdfFile = storage_path('app/public/' . $filePath);
            
            // Tạo đường dẫn lưu ảnh preview
            $imagePath = 'uploads/previews/' . pathinfo($filePath, PATHINFO_FILENAME) . '_preview.jpg';
            $fullImagePath = storage_path('app/public/' . $imagePath);
            
            // Chuyển đổi trang đầu tiên của PDF sang ảnh JPG bằng Poppler
            $command = "pdftoppm -jpeg -f 1 -singlefile \"$pdfFile\" \"" . dirname($fullImagePath) . '/' . pathinfo($fullImagePath, PATHINFO_FILENAME) . "\"";
            exec($command);
            
            // Kiểm tra xem ảnh có được tạo không
            if (!file_exists($fullImagePath)) {
                return response()->json([
                    'message' => 'Không thể tạo ảnh xem trước.'
                ], 500);
            }
            
            // chỉnh kích thước và nén ảnh
            $manager = new ImageManager(Driver::class);
            $image = $manager->read($fullImagePath);
            $image->resize(800, null)->scaleDown();
            
            // Nén ảnh và lưu lại
            $encodedImage = $image->toJpeg(80); // 80 là chất lượng ảnh
            file_put_contents($fullImagePath, $encodedImage);
            
            $document = Document::where('Dcm_id', $id)->first();
            if ($document) {
                $document->dcm_preview_path = $imagePath;
                if ($document->save()) {
                    Log::info('Đã lưu preview path thành công: ' . $imagePath);
                } else {
                    Log::error('Lưu preview path thất bại.');
                }
            }
            
            return response()->json([
                'preview_url' => asset('storage/' . $imagePath),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error generating preview',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function getFile(Request $request)
    {
        $subject = $request->input('subject');
    
        // Lấy danh sách file theo subject, nếu không có thì lấy tất cả
        $query = Document::select('Dcm_id', 'dcm_title', 'dcm_file_path', 'dcm_preview_path', 'subject');
    
        if ($subject) {
            $query->where('subject', $subject);
        }
    
        $files = $query->get();
    
        $files = $files->map(function ($file) {
            $file->preview_url = $file->dcm_preview_path ? asset('storage/' . $file->dcm_preview_path) : null;
            $file->file_url = asset('storage/' . $file->dcm_file_path);
            return $file;
        });
    
        return response()->json($files);
    }

    
    /**
     * Trả về file đầy đủ khi người dùng click vào.
     */
    public function getFullFile($id)
    {
        // Tìm tài liệu theo ID
        $document = Document::find($id);
        
        if (!$document) {
            return response()->json(['message' => 'File not found'], 404);
        }
        
        // Kiểm tra xem file có tồn tại trong storage không
        $filePath = Storage::disk('public')->path($document->dcm_file_path);
        
        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File not found in storage'], 404);
        }
        // Lấy title, file_url và preview_url
        $title = $document->dcm_title;
        $fileUrl = asset('storage/' . $document->dcm_file_path);

        // Trả về JSON chứa title, file_url và preview_url
        return response()->json([
            'title' => $title,
            'file_url' => $fileUrl,
        ], 200);
    }
    
    public function getPreviews(Request $request)
    {
        $subject = $request->query('subject');
    
        $query = Document::query();
        if ($subject) {
            $query->where('subject', $subject);
        }
        $previews = $query->get();
    
        return response()->json($previews);
    }
    
    public function getSubjects()
    {
        $subjects = Document::select('subject')->distinct()->pluck('subject');
        return response()->json($subjects);
    }
}
