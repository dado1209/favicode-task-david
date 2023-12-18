@if (isset($file))
    <div class="modal fade" id="popupModal_{{ $file->id }}" tabindex="-1" role="dialog"
        aria-labelledby="popupModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="popupModalLabel_{{ $file->id }}"
                        style="max-width: 80%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="width: 20%;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="text-align: center;">
                    @php
                        $parts = explode('.', $file->name);
                        $extension = end($parts);
                    @endphp

                    @if (in_array($extension, ['png', 'jpg']))
                        <img src="{{ route('file.show', ['id' => $file->id]) }}"
                            style="max-width: 100%; max-height: 80vh;">
                    @elseif (in_array($extension, ['txt']))
                        <iframe src="{{ route('file.show', ['id' => $file->id]) }}" frameborder="0"
                            style="width:100%;min-height:640px;border: none; background-color: white; color: black;"></iframe>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
