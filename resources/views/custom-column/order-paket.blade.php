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
<div class="@if ($status) td-color-success @else td-color-danger @endif">
    {{ $data }}
</div>