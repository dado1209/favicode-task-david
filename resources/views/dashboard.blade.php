<!-- dashboard.blade.php -->

@extends('layout') <!-- Assuming you have a layout file, adjust accordingly -->

@section('content')
    <h1>User Dashboard</h1>

    @if(count($files) > 0)
        <div style="text-align: center;">
            <table class="table" style="width: 80%;">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Size</th>
                        <th>Type</th>
                        <th>Actions</th> <!-- New column for download icon -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($files as $file)
                        <tr style="background-color: #f2f2f2;">
                            <td>{{ $file->name }}</td>
                            <td>{{ $file->size }} bytes</td>
                            <td>{{ $file->type }}</td>
                            <td>
                                <!-- Download icon with a link to the download route -->
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
