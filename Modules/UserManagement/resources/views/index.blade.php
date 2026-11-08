@extends('layouts.app')

@section('title', 'Manajemen User - SiPustaka')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Manajemen User</h4>
    <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah User</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr><th>#</th><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            @forelse ($users as $u)
                <tr>
                    <td>{{ $users->firstItem() + $loop->index }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td><span class="badge {{ $u->role === 'admin' ? 'bg-danger' : 'bg-secondary' }} text-uppercase">{{ $u->role }}</span></td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-warning">Ubah</a>
                        <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('Hapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada user.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $users->links() }}</div>
@endsection
