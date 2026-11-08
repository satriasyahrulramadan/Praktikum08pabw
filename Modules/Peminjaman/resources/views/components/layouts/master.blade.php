@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Peminjaman</h4>
    <button class="btn btn-primary" onclick="openForm()">Tambah</button>
</div>
<div id="notif"></div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Peminjam</th>
            <th>Buku</th>
            <th>Pinjam</th>
            <th>Kembali</th>
            <th>Status</th>
            <th>Bukti</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody id="rows"></tbody>
</table>

<div class="modal fade" id="modalForm" tabindex="-1">
    <div class="modal-dialog">
        <form id="form" class="modal-content" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 id="modalTitle" class="modal-title"></h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="record_id">
                <div class="mb-3">
                    <label class="form-label">Nama Peminjam</label>
                    <input type="text" name="nama_peminjam" class="form-control">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Judul Buku</label>
                    <input type="text" name="judul_buku" class="form-control">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" class="form-control">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali" class="form-control">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="Dipinjam">Dipinjam</option>
                        <option value="Dikembalikan">Dikembalikan</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Bukti (jpg/png/pdf, maks 2 MB)</label>
                    <input type="file" name="bukti" class="form-control">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const headers = {
    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
    'Accept': 'application/json',
};
const modal = new bootstrap.Modal('#modalForm');
const form = document.getElementById('form');
const rows = document.getElementById('rows');
let items = [];

const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

function notify(msg, type = 'success') {
    document.getElementById('notif').innerHTML = `<div class="alert alert-${type}">${esc(msg)}</div>`;
}

function clearErrors() {
    form.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach((el) => (el.textContent = ''));
}

async function load() {
    const res = await fetch('{{ route('peminjaman.data') }}', { headers });
    items = await res.json();
    rows.innerHTML = items.map((p, i) => `
        <tr>
            <td>${i + 1}</td>
            <td>${esc(p.nama_peminjam)}</td>
            <td>${esc(p.judul_buku)}</td>
            <td>${esc(p.tanggal_pinjam?.substring(0, 10))}</td>
            <td>${esc(p.tanggal_kembali?.substring(0, 10))}</td>
            <td>${esc(p.status)}</td>
            <td>${p.bukti_url ? `<a href="${esc(p.bukti_url)}" target="_blank">Lihat</a>` : '-'}</td>
            <td class="d-flex gap-1">
                <button class="btn btn-sm btn-warning" onclick="openForm(${p.id})">Ubah</button>
                <button class="btn btn-sm btn-danger" onclick="hapus(${p.id})">Hapus</button>
            </td>
        </tr>`).join('') || '<tr><td colspan="8" class="text-center">Belum ada data.</td></tr>';
}

function openForm(id = null) {
    clearErrors();
    form.reset();
    const p = items.find((x) => x.id === id);
    document.getElementById('modalTitle').textContent = p ? 'Ubah Peminjaman' : 'Tambah Peminjaman';
    form.record_id.value = p ? p.id : '';
    if (p) {
        form.nama_peminjam.value = p.nama_peminjam;
        form.judul_buku.value = p.judul_buku;
        form.tanggal_pinjam.value = p.tanggal_pinjam?.substring(0, 10) ?? '';
        form.tanggal_kembali.value = p.tanggal_kembali?.substring(0, 10) ?? '';
        form.status.value = p.status;
    }
    modal.show();
}

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearErrors();

    const id = form.record_id.value;
    const fd = new FormData(form);
    fd.delete('record_id');
    if (id) fd.append('_method', 'PUT');

    const url = id ? `{{ url('peminjaman') }}/${id}` : '{{ route('peminjaman.store') }}';
    const res = await fetch(url, { method: 'POST', headers, body: fd });
    const json = await res.json();

    if (res.status === 422) {
        Object.entries(json.errors).forEach(([field, msgs]) => {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                input.parentElement.querySelector('.invalid-feedback').textContent = msgs[0];
            }
        });
        return;
    }

    if (!res.ok) {
        notify(json.message ?? 'Terjadi kesalahan.', 'danger');
        return;
    }

    modal.hide();
    notify(json.message);
    load();
});

async function hapus(id) {
    if (!confirm('Hapus data ini?')) return;
    const res = await fetch(`{{ url('peminjaman') }}/${id}`, {
        method: 'POST',
        headers,
        body: new URLSearchParams({ _method: 'DELETE' }),
    });
    const json = await res.json();
    notify(json.message, res.ok ? 'success' : 'danger');
    load();
}

load();
</script>
@endpush