@php
    $status = false;
    $data = json_decode($getState());
    if ($data && is_object($data) && $data->status == 1) {
        $status = true;
        $data = $data->data;
    } elseif (is_object($data)) {
        $data = $data->data;
    }
@endphp
<div style="@if ($status) background-color: #28a745; color: white; @else background-color: #dc3545; color: white; @endif padding: 0.25rem 0.5rem; border-radius: 0.25rem; display: inline-block; font-size: 0.75rem; font-weight: 700;">
    @php
        echo $data;
    @endphp
</div>
