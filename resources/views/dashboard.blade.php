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

                                    <button class="btn btn-warning" onclick="toggleOptions('{{ $file->id }}')">
                                        <i class="fas fa-cogs"></i> Settings
                                    </button>
                                </div>
                                <div class="options-container hidden" id="options-container-{{ $file->id }}">
                                    <form action="{{ route('file.settings', ['id' => $file->id]) }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="newFileName">File Name</label>
                                            <input type="text" class="form-control" id="newFileName"
                                                name="newFileName" value="{{ $file->name }}">
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
                </tbody>
            </table>
        </div>

        <script>
            function toggleOptions(fileId) {
                const optionsContainer = document.querySelector('#options-container-' + fileId);
                optionsContainer.classList.toggle('show-options');
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
@endsection
