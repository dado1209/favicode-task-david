@extends('layout')
@section('content')
    @if (session('Error'))
        <div class="alert alert-danger text-center" style="width: 80%; margin: 0 auto;">
            {{ session('Error') }}
        </div>
    @endif
    @if (session('Update'))
        <div class="alert alert-success text-center" style="width: 80%; margin: 0 auto;">
            {{ session('Update') }}
        </div>
    @endif

    @if (count($files) > 0)
        <div style="text-align: center;">
            <table class="table" style="width: 80%;">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Size</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($files as $file)
                        @include('file.display-file-modal')
                        <tr style="background-color: #f2f2f2;">
                            <td style="white-space: nowrap;"
                                onclick="openPopup('{{ $file->id }}', '{{ $file->name }}')">{{ $file->name }}</td>
                            <td>{{ $file->size / 1000 }} KB</td>
                            <td>{{ $file->type }}</td>
                            <td>
                                @include('file.file-buttons')
                                @include('file.settings-form')
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <script>
            function toggleOptions(fileId) {
                const optionsContainer = document.querySelector('#options-container-' + fileId);
                optionsContainer.classList.toggle('show-options');
            }

            function showDeleteConfirmation(fileId) {
                const confirmDelete = confirm("Are you sure you want to delete this file?");

                if (confirmDelete) {
                    const deleteForm = document.getElementById('deleteForm_' + fileId);
                    deleteForm.submit();
                }
            }

            function shareFile(downloadUrl) {
                const tempInput = document.createElement('input');
                document.body.appendChild(tempInput);
                tempInput.value = downloadUrl;
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
                alert('Link copied to clipboard!');
            }

            function openPopup(fileId, fileName) {
                // Set the modal ID
                var modalId = '#popupModal_' + fileId;

                // Check if the modal exists
                if ($(modalId).length) {
                    // Extract the file extension from the file name
                    var fileExtension = fileName.split('.').pop().toLowerCase();

                    // Define an array of allowed image file extensions
                    var allowedExtensions = ['png', 'jpg', 'txt'];

                    // Check if the file extension is allowed
                    if (allowedExtensions.includes(fileExtension)) {
                        // Set the modal title
                        $(modalId + 'Label').text(fileName);

                        // Display the Bootstrap modal
                        $(modalId).modal('show');
                    } else {
                        console.error('File type not supported for preview: ' + fileExtension);
                    }
                } else {
                    console.error('Modal not found with ID: ' + modalId);
                }
            }
        </script>

        <style>
            .hidden {
                display: none;
            }

            .show-options {
                display: block;
            }
        </style>
    @else
        <p>No files found.</p>
    @endif
    @include('file.display-storage')
    @include('file.display-file-modal')
@endsection
