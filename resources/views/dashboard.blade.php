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
                        <tr style="background-color: #f2f2f2;">
                            <td>{{ $file->name }}</td>
                            <td>{{ $file->size / 1000 }} KB</td>
                            <td>{{ $file->type }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('file.download', ['id' => $file->id]) }}" class="btn btn-primary">
                                        <i class="fas fa-download"></i> Download
                                    </a>

                                    @if ($file->type === 'public')
                                        <button class="btn btn-success"
                                            onclick="shareFile('{{ route('file.download', ['id' => $file->id]) }}')">
                                            <i class="fas fa-share"></i> Share
                                        </button>
                                    @endif

                                    <button class="btn btn-warning" onclick="toggleOptions('{{ $file->id }}')">
                                        <i class="fas fa-cogs"></i> Settings
                                    </button>

                                    <form id="deleteForm_{{ $file->id }}"
                                        action="{{ route('file.delete', ['id' => $file->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button" class="btn btn-danger"
                                            onclick="showDeleteConfirmation('{{ $file->id }}')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                                <div class="options-container hidden" id="options-container-{{ $file->id }}">
                                    <form action="{{ route('file.update', ['id' => $file->id]) }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="newFileName">File Name</label>
                                            <input type="text" class="form-control" id="newFileName" name="newFileName"
                                                value="{{ $file->name }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="newFileType">File Type</label>
                                            <select class="form-control" id="newFileType" name="newFileType">
                                                <option value="public">Public</option>
                                                <option value="private">Private</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-success">Save</button>
                                        <button type="button" class="btn btn-secondary"
                                            onclick="toggleOptions('{{ $file->id }}')">Cancel</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    <!-- ... (remaining content) ... -->

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

    @if (count($files) > 0)
        @php
            $totalSize = $files->pluck('size')->sum() / 1000000000; // add all the sizes of files and convert bytes to gigabytes;
            $storageUsageRatio = $totalSize / $user->allowedStorageGB;
        @endphp

        <div class="storage-usage-bar" style="position: fixed; bottom: 10px; right: 10px;">
            Storage Capacity
            <div class="progress" style="width: 150px;">
                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $storageUsageRatio }}%;"
                    aria-valuenow="{{ $storageUsageRatio }}" aria-valuemin="0" aria-valuemax="100">
                    {{ number_format($storageUsageRatio, 2) }}%
                </div>
            </div>
        </div>
    @endif
@endsection
