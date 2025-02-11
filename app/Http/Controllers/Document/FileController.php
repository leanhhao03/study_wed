<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Smalot\PdfParser\Parser;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        // Kiểm tra người dùng đã đăng nhập
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        // Kiểm tra xem có file được gửi lên không
        if (!$request->hasFile('file')) {
            return response()->json(['message' => 'No file uploaded'], 400);
        }
    
        // Validate dữ liệu đầu vào
        $request->validate([
            'file' => 'required|mimes:doc,docx,pdf|max:5120',
            'title_file' => 'required|max:256|string',
        ]);
    
        try {

            // Lấy thông tin file được upload
            $uploadedFile = $request->file('file');
            if (!$uploadedFile->isValid()) {
                return response()->json(['message' => 'Invalid file'], 400);
            }
    
            // Đặt tên file và lưu trữ file
            $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
            $filePath = $uploadedFile->storeAs('uploads', $fileName, 'public');
    
            if (!$filePath) {
                return response()->json(['message' => 'File upload failed'], 500);
            }
    
            // Lưu thông tin vào database
            $file = new Document();
            $file->dcm_description = $uploadedFile->getClientOriginalName();
            $file->dcm_title = $request->input('title_file');
            $file->dcm_file_path = $filePath;
            $file->us_id = 1;
    
            if (!$file->save()) {
                return response()->json(['message' => 'Database insert failed'], 500);
            }
    
            return response()->json([
                'message' => 'File uploaded successfully!',
                'file' => $file,
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while uploading the file.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
        public function convert(Request $request)
        {
            $request->validate([
                'file' => 'required|mimes:doc,docx|max:5120'
            ]);
    
            $file = $request->file('file');
            
            // Kiểm tra nếu file không tồn tại hoặc bị lỗi
            if (!$file->isValid()) {
                return response()->json(['message' => 'Invalid file'], 400);
            }
    
            try {
                // Đọc file Word
                $phpWord = IOFactory::load($file->getPathname());
    
                // Chuyển Word sang HTML trước (cần cho PDF)
                $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
                ob_start();
                $htmlWriter->save('php://output');
                $htmlContent = ob_get_clean();
    
                // Chuyển HTML sang PDF với Dompdf
                $dompdf = new Dompdf(new Options(['isHtml5ParserEnabled' => true]));
                $dompdf->loadHtml($htmlContent);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
    
                // Lưu file PDF vào storage/public/uploads
                $pdfFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.pdf';
                $pdfPath = 'uploads/' . $pdfFileName;
                Storage::disk('public')->put($pdfPath, $dompdf->output());
    
                return response()->json([
                    'message' => 'File converted to PDF successfully!',
                    'pdf_url' => asset('storage/' . $pdfPath)
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Error converting file', 'error' => $e->getMessage()], 500);
            }
        }

        public function getFile(){
            $files = Storage::disk('public')->allFiles('uploads');
            return response()->json($files);
        }
            /**
             * Trả về nội dung trang đầu tiên của file (preview).
             */
            public function getPreviewPage($id)
            {
                // Tìm tài liệu theo ID
                $document = Document::find($id);
        
                if (!$document) {
                    return response()->json(['message' => 'File not found'], 404);
                }
        
                // Kiểm tra xem file có phải là PDF không và file có tồn tại trong storage không
                $filePath = storage_path('app/public/' . $document->dcm_file_path);
                if (!file_exists($filePath)) {
                    return response()->json(['message' => 'File not found in storage'], 404);
                }
        
                // Nếu là PDF, trích xuất trang đầu
                if (pathinfo($document->dcm_file_path, PATHINFO_EXTENSION) == 'pdf') {
                    return $this->getPdfPreview($filePath);
                }
        
                // Nếu không phải PDF, có thể trả về hình ảnh hoặc một số thông tin khác
                return response()->json([
                    'message' => 'Preview for this file type is not supported',
                ], 400);
            }
        
            /**
             * Trích xuất trang đầu tiên của file PDF.
             */
            private function getPdfPreview($filePath)
            {
                $parser = new Parser();
                $pdf = $parser->parseFile($filePath);
        
                // Lấy văn bản của trang đầu tiên
                $text = $pdf->getText();
        
                return response()->json([
                    'preview_text' => substr($text, 0, 500),  // Chỉ lấy 500 ký tự đầu tiên của trang
                ], 200);
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
                $filePath = storage_path('app/public/' . $document->dcm_file_path);
        
                if (!file_exists($filePath)) {
                    return response()->json(['message' => 'File not found in storage'], 404);
                }
        
                 // Trả về file PDF để hiển thị trực tiếp trên trình duyệt
            return response()->file($filePath, [
             'Content-Type' => 'application/pdf',       // Đảm bảo rằng content type là PDF
             'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"', // Hiển thị file trực tiếp
        ]);
    }
}
        