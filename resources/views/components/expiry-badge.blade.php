@props(['medicine'])

@if ($medicine->is_expired)
    <span class="badge badge-danger">Kedaluwarsa</span>
@elseif ($medicine->is_expiring_soon)
    <span class="badge badge-warning">Segera kedaluwarsa</span>
@else
    <span class="badge badge-success">Masa simpan baik</span>
@endif
