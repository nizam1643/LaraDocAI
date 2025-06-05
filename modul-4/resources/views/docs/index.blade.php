@extends('layouts.app')

@section('content')
    <style>
        * {
            box-sizing: border-box;
        }

        .swagger-container {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #fafafa;
            min-height: 100vh;
            padding: 0;
            margin: 0;
        }

        .swagger-header {
            background: linear-gradient(135deg, #89bf04 0%, #7aa304 100%);
            color: white;
            padding: 40px 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            margin-bottom: 40px;
        }

        .swagger-header h1 {
            text-align: center;
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 12px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .swagger-header p {
            text-align: center;
            font-size: 1.2rem;
            opacity: 0.9;
            margin: 0;
        }

        .docs-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .search-container {
            margin-bottom: 30px;
            text-align: center;
        }

        .search-box {
            width: 100%;
            max-width: 500px;
            padding: 15px 25px;
            border: 2px solid #e3e8f0;
            border-radius: 30px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .search-box:focus {
            outline: none;
            border-color: #007ACC;
            box-shadow: 0 0 0 4px rgba(0, 122, 204, 0.15);
            transform: translateY(-2px);
        }

        .doc-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            overflow: hidden;
            border: 1px solid #e3e8f0;
            transition: all 0.3s ease;
        }

        .doc-section:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .doc-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid #e3e8f0;
            padding: 25px 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }

        .doc-header:hover {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        }

        .doc-header.active {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-left: 6px solid #007ACC;
        }

        .doc-header::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 0;
            background: #007ACC;
            transition: width 0.3s ease;
        }

        .doc-header.active::before {
            width: 6px;
        }

        .doc-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            display: flex;
            align-items: center;
            margin: 0;
        }

        .doc-badge {
            background: linear-gradient(135deg, #007ACC 0%, #0056b3 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-right: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0, 122, 204, 0.3);
        }

        .collapse-icon {
            font-size: 1.5rem;
            color: #666;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .collapse-icon.active {
            transform: rotate(180deg);
            color: #007ACC;
        }

        .doc-content {
            display: none;
            background: white;
        }

        .doc-content.active {
            display: block;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .doc-body {
            padding: 30px;
        }

        .doc-meta {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 4px solid #007ACC;
            padding: 20px 25px;
            margin-bottom: 25px;
            border-radius: 0 8px 8px 0;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .meta-title {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 1.1rem;
        }

        .meta-content {
            color: #5a6c7d;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .code-block {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
            color: #e2e8f0;
            padding: 25px;
            border-radius: 10px;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', 'Consolas', monospace;
            font-size: 0.9rem;
            line-height: 1.6;
            overflow-x: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            border: 1px solid #4a5568;
        }

        .code-block::-webkit-scrollbar {
            height: 8px;
        }

        .code-block::-webkit-scrollbar-track {
            background: #2d3748;
            border-radius: 4px;
        }

        .code-block::-webkit-scrollbar-thumb {
            background: #4a5568;
            border-radius: 4px;
        }

        .code-block::-webkit-scrollbar-thumb:hover {
            background: #718096;
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .no-results h3 {
            color: #495057;
            margin-bottom: 15px;
            font-size: 1.5rem;
        }

        .no-results p {
            font-size: 1.1rem;
            opacity: 0.8;
        }

        /* Stats Bar */
        .stats-bar {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            border: 1px solid #e3e8f0;
        }

        .stats-item {
            display: inline-block;
            margin: 0 30px;
            color: #6c757d;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: #007ACC;
            display: block;
        }

        .stats-label {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 5px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .swagger-header h1 {
                font-size: 2.2rem;
            }

            .swagger-header p {
                font-size: 1rem;
            }

            .doc-title {
                font-size: 1.3rem;
            }

            .doc-header,
            .doc-body {
                padding: 20px;
            }

            .docs-wrapper {
                padding: 0 15px;
            }

            .stats-item {
                display: block;
                margin: 15px 0;
            }

            .search-box {
                padding: 12px 20px;
                font-size: 1rem;
            }
        }
    </style>

    <div class="swagger-container">
        <div class="swagger-header">
            <div class="container">
                <h1>📚 Dokumentasi Sistem</h1>
                <p>Laravel System Documentation</p>
            </div>
        </div>

        <div class="docs-wrapper">
            <div class="search-container">
                <input type="text" class="search-box" placeholder="🔍 Cari dokumentasi..." id="searchBox">
            </div>

            <div class="stats-bar">
                <div class="stats-item">
                    <span class="stats-number">{{ count($docs) }}</span>
                    <span class="stats-label">Total Docs</span>
                </div>
                <div class="stats-item">
                    <span class="stats-number">{{ $docs->filter(fn($doc) => str_contains(strtolower($doc->class_name), 'controller'))->count() }}</span>
                    <span class="stats-label">Controllers</span>
                </div>
                <div class="stats-item">
                    <span class="stats-number">{{ $docs->filter(fn($doc) => str_contains(strtolower($doc->class_name), 'service'))->count() }}</span>
                    <span class="stats-label">Services</span>
                </div>
            </div>

            <div id="docsContainer">
                @foreach ($docs as $doc)
                    <div class="doc-section" data-search="{{ strtolower($doc->class_name . ' ' . $doc->documentation) }}">
                        <div class="doc-header" onclick="toggleDoc(this)">
                            <div class="doc-title">
                                <span class="doc-badge">
                                    @if (str_contains(strtolower($doc->class_name), 'controller'))
                                        CONTROLLER
                                    @elseif(str_contains(strtolower($doc->class_name), 'service'))
                                        SERVICE
                                    @elseif(str_contains(strtolower($doc->class_name), 'model'))
                                        MODEL
                                    @elseif(str_contains(strtolower($doc->class_name), 'middleware'))
                                        MIDDLEWARE
                                    @else
                                        CLASS
                                    @endif
                                </span>
                                {{ $doc->class_name }}
                            </div>
                            <span class="collapse-icon">▼</span>
                        </div>
                        <div class="doc-content">
                            <div class="doc-body">
                                <div class="doc-meta">
                                    <div class="meta-title">📋 Class Information</div>
                                    <div class="meta-content">
                                        <strong>Class Name:</strong> {{ $doc->class_name }}<br>
                                        <strong>Generated:</strong> {{ $doc->created_at ? $doc->created_at->format('d M Y, H:i') : 'N/A' }}<br>
                                        <strong>Type:</strong>
                                        @if (str_contains(strtolower($doc->class_name), 'controller'))
                                            Laravel Controller
                                        @elseif(str_contains(strtolower($doc->class_name), 'service'))
                                            Service Class
                                        @elseif(str_contains(strtolower($doc->class_name), 'model'))
                                            Eloquent Model
                                        @else
                                            PHP Class
                                        @endif
                                    </div>
                                </div>

                                <div class="code-block">{{ $doc->documentation }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if (count($docs) === 0)
                <div class="no-results">
                    <h3>🚫 Tiada dokumentasi dijumpai</h3>
                    <p>Belum ada dokumentasi yang tersedia dalam sistem</p>
                </div>
            @endif

            <div id="noResults" class="no-results" style="display: none;">
                <h3>🔍 Tiada hasil dijumpai</h3>
                <p>Cuba cari dengan kata kunci yang berbeza</p>
            </div>
        </div>
    </div>

    <script>
        function toggleDoc(header) {
            const content = header.nextElementSibling;
            const icon = header.querySelector('.collapse-icon');
            const isActive = content.classList.contains('active');

            // Close all others
            document.querySelectorAll('.doc-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.doc-header').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.collapse-icon').forEach(el => el.classList.remove('active'));

            if (!isActive) {
                content.classList.add('active');
                header.classList.add('active');
                icon.classList.add('active');
            }
        }

        document.getElementById('searchBox').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const docSections = document.querySelectorAll('.doc-section');
            const noResults = document.getElementById('noResults');
            const docsContainer = document.getElementById('docsContainer');
            let hasResults = false;

            docSections.forEach(section => {
                const searchData = section.getAttribute('data-search');
                const title = section.querySelector('.doc-title').textContent.toLowerCase();

                if (searchData.includes(searchTerm) || title.includes(searchTerm) || searchTerm === '') {
                    section.style.display = 'block';
                    hasResults = true;
                } else {
                    section.style.display = 'none';
                }
            });

            docsContainer.style.display = hasResults ? 'block' : 'none';
            noResults.style.display = hasResults ? 'none' : 'block';
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Clear all active states first
            document.querySelectorAll('.doc-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.doc-header').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.collapse-icon').forEach(el => el.classList.remove('active'));

            // Activate first doc section
            const firstDoc = document.querySelector('.doc-header');
            if (firstDoc) {
                const firstContent = firstDoc.nextElementSibling;
                const firstIcon = firstDoc.querySelector('.collapse-icon');

                firstDoc.classList.add('active');
                if (firstContent) firstContent.classList.add('active');
                if (firstIcon) firstIcon.classList.add('active');
            }
        });
    </script>
@endsection
