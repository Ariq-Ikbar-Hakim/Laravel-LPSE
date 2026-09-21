<p>Halo {{ $transfer->dariUser->nama }},</p>

@if ($event === 'submitted')
    <p>Pengajuan transfer jabatan dan paket Anda sudah diterima dan menunggu persetujuan admin.</p>
@else
    <p>Pengajuan transfer jabatan dan paket Anda telah {{ $transfer->status === 'disetujui' ? 'disetujui' : 'ditolak' }} oleh admin.</p>
    @if ($transfer->catatan_admin)
        <p>Catatan admin: {{ $transfer->catatan_admin }}</p>
    @endif
@endif

<p>Status: <strong>{{ strtoupper($transfer->status) }}</strong></p>
<p>Terima kasih.<br>BANGEDI PBJ</p>
