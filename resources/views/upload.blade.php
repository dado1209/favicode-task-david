@extends('layout')

@section('content')
    <div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
        <div style="text-align: center;">
            @if (session('success'))
                <div style="color: green;">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div style="color: red;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('upload') }}" method="post" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <div id="drop-area" class="col-md-8 offset-md-2" style="border: 2px dashed #ccc; border-radius: 8px; padding: 40px; text-align: center; transition: border 0.3s ease;">
                    <label for="file" id="file-label" style="cursor: pointer;">Choose File or Drag and Drop</label>
                    <input type="file" name="file" id="file" class="file-input" required style="display: none;">
                    <div id="file-name" style="margin-top: 20px; font-weight: bold;"></div>
                </div>

                <select name="type" id="type" required style="margin-top: 20px;">
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select>

                <div class="col-md-8 offset-md-2" style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">
                        Upload
                    </button>
                </div>
            </form>

            <script>
                const dropArea = document.getElementById('drop-area');
                const fileInput = document.getElementById('file');
                const fileNameDisplay = document.getElementById('file-name');

                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropArea.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropArea.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropArea.addEventListener(eventName, unhighlight, false);
                });

                function highlight() {
                    dropArea.classList.add('highlight');
                }

                function unhighlight() {
                    dropArea.classList.remove('highlight');
                }

                dropArea.addEventListener('drop', handleDrop, false);

                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;

                    fileInput.files = files;

                    handleFiles(files);
                }

                fileInput.addEventListener('change', function() {
                    handleFiles(this.files);
                });

                function handleFiles(files) {
                    if (files.length > 0) {
                        fileNameDisplay.textContent = files[0].name;
                    } else {
                        fileNameDisplay.textContent = '';
                    }
                }
            </script>

            <style>
                #drop-area.highlight {
                    border-color: #2185d0;
                }

                .file-input {
                    display: none;
                }

                #file-label {
                    cursor: pointer;
                }
            </style>
        </div>
    </div>
@endsection
