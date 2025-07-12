@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-box"></i> Daftar Produk</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Produk
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" width="50" height="50" 
                                         class="rounded">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $product->name }}</strong><br>
                                <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $product->category->name }}</span>
                            </td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>
                                @if($product->stock > 0)
                                    <span class="badge bg-success">{{ $product->stock }}</span>
                                @else
                                    <span class="badge bg-danger">Habis</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{ $products->links() }}
        @else
            <div class="text-center py-4">
                <i class="fas fa-box fa-3x text-muted mb-3"></i>
                <p class="text-muted">Belum ada produk yang ditambahkan</p>
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Produk Pertama
                </a>
            </div>
        @endif
    </div>
</div>

@if(isset($tokenInfo))
<script>
    localStorage.setItem('user_id', '{{ $tokenInfo['id'] ?? '' }}');
    localStorage.setItem('email', '{{ $tokenInfo['email'] ?? '' }}');
    localStorage.setItem('login_at', '{{ $tokenInfo['login_at'] ?? '' }}');
</script>
@endif

    @if(session('auth_token'))
    <div class="alert alert-secondary small">
        <strong>Token Anda:</strong> {{ session('auth_token') }}
    </div>
@endif

@if(isset($decryptedToken) && $decryptedToken)
    @php $tokenInfo = json_decode($decryptedToken, true); @endphp

    @if(is_array($tokenInfo))
        <div class="alert alert-info small">
            <strong>ID:</strong> {{ $tokenInfo['id'] ?? '-' }} |
            <strong>Email:</strong> {{ $tokenInfo['email'] ?? '-' }} |
            <strong>Login:</strong> {{ $tokenInfo['login_at'] ?? '-' }}
        </div>
    @else
        <div class="alert alert-danger small">
            Token tidak valid atau gagal didekripsi.
        </div>
    @endif
@endif

@endsection