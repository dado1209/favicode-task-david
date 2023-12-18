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
