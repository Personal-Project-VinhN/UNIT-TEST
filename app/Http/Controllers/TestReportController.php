<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

/**
 * Controller for serving test reports via web
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class TestReportController extends Controller
{
    /**
     * Display coverage report
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function coverage(Request $request)
    {
        // Optional: Add authentication middleware here
        // $this->middleware('auth');
        
        $path = $request->get('path', 'index.html');
        $filePath = base_path('tests/results/coverage/' . $path);
        
        if (!File::exists($filePath) || !File::isFile($filePath)) {
            return view('test-reports.not-found', [
                'title' => 'Coverage Report Not Found',
                'message' => 'Coverage report not found. Please run: .\deploy\generate-test-reports.bat',
                'backUrl' => route('test-reports.index')
            ]);
        }
        
        // Security: Only allow files in coverage directory
        $basePath = base_path('tests/results/coverage');
        $realFilePath = realpath($filePath);
        $realBasePath = realpath($basePath);
        
        // Check if file is within coverage directory (PHP 8.0+ compatible)
        if (!$realFilePath || !$realBasePath || strpos($realFilePath, $realBasePath) !== 0) {
            abort(403, 'Access denied');
        }
        
        $contentType = mime_content_type($filePath);
        return response()->file($filePath, ['Content-Type' => $contentType]);
    }
    
    /**
     * Display TestDox report
     *
     * @return \Illuminate\Http\Response
     */
    public function testdox()
    {
        $file = base_path('tests/results/testdox.html');
        
        if (!file_exists($file)) {
            return view('test-reports.not-found', [
                'title' => 'TestDox Report Not Found',
                'message' => 'TestDox report not found. Please run: .\deploy\generate-test-reports.bat',
                'backUrl' => route('test-reports.index')
            ]);
        }
        
        return response()->file($file);
    }
    
    /**
     * List all available reports
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $reports = [
            'coverage' => [
                'available' => file_exists(base_path('tests/results/coverage/index.html')),
                'path' => base_path('tests/results/coverage/index.html'),
                'url' => route('test-reports.coverage'),
                'description' => 'HTML Coverage Report - Shows line-by-line code coverage'
            ],
            'testdox' => [
                'available' => file_exists(base_path('tests/results/testdox.html')),
                'path' => base_path('tests/results/testdox.html'),
                'url' => route('test-reports.testdox'),
                'description' => 'TestDox HTML Report - Human readable test results'
            ],
        ];
        
        return view('test-reports.index', compact('reports'));
    }
    
}

