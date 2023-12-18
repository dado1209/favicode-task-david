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
