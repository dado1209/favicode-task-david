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
