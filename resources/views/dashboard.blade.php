@extends('layout')

@section('content')
    @if(session('Error'))
        <div class="alert alert-danger text-center" style="width: 80%; margin: 0 auto;">
            {{ session('Error') }}
        </div>
    @endif



    @if(count($files) > 0)
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
                            <td>{{ $file->size / 1000}} KB</td>
                            <td>{{ $file->type }}</td>
                            <td>
                                <a href="{{ route('download', ['id' => $file->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>No files found.</p>
    @endif
@endsection
