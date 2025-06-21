@extends('layouts.admin')

@section('title', 'Edit Banner')

@section('page-title', 'Edit Banner')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-admin">
            <div class="card-header">
                <h5 class="mb-0">Form Edit Banner</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $banner->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Subjudul</label>
                        <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}">
                        @error('subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar Banner</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, GIF. Ukuran maks: 2MB. Biarkan kosong jika tidak ingin mengubah gambar.</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="link" class="form-label">Link URL</label>
                        <input type="url" class="form-control @error('link') is-invalid @enderror" id="link" name="link" value="{{ old('link', $banner->link) }}" placeholder="https://...">
                        @error('link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="button_text" class="form-label">Teks Tombol</label>
                        <input type="text" class="form-control @error('button_text') is-invalid @enderror" id="button_text" name="button_text" value="{{ old('button_text', $banner->button_text) }}">
                        @error('button_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="position" class="form-label">Posisi <span class="text-danger">*</span></label>
                        <select class="form-select @error('position') is-invalid @enderror" id="position" name="position" required>
                            <option value="">Pilih Posisi</option>
                            @foreach($positions as $value => $label)
                                <option value="{{ $value }}" {{ old('position', $banner->position) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', $banner->start_date ? $banner->start_date->format('Y-m-d') : '') }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Tanggal Berakhir</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', $banner->end_date ? $banner->end_date->format('Y-m-d') : '') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Urutan</label>
                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" min="0">
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif</label>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card card-admin">
            <div class="card-header">
                <h5 class="mb-0">Preview</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <img id="image-preview" src="{{ asset('storage/' . $banner->image) }}" class="img-fluid rounded" alt="{{ $banner->title }}">
                </div>
                <div class="text-center">
                    <h5 id="title-preview">{{ $banner->title }}</h5>
                    <p id="subtitle-preview" class="text-muted">{{ $banner->subtitle ?? 'Subjudul Banner' }}</p>
                    <button id="button-preview" class="btn btn-primary">{{ $banner->button_text ?? 'Lihat Detail' }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        const titleInput = document.getElementById('title');
        const titlePreview = document.getElementById('title-preview');
        const subtitleInput = document.getElementById('subtitle');
        const subtitlePreview = document.getElementById('subtitle-preview');
        const buttonTextInput = document.getElementById('button_text');
        const buttonPreview = document.getElementById('button-preview');
        
        // Image preview
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Title preview
        titleInput.addEventListener('input', function() {
            titlePreview.textContent = this.value || 'Judul Banner';
        });
        
        // Subtitle preview
        subtitleInput.addEventListener('input', function() {
            subtitlePreview.textContent = this.value || 'Subjudul Banner';
        });
        
        // Button text preview
        buttonTextInput.addEventListener('input', function() {
            buttonPreview.textContent = this.value || 'Lihat Detail';
        });
    });
</script>
@endpush 