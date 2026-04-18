@extends('layouts.app')

@section('content')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<div class="px-6 py-8 md:px-12 md:py-10 animate-fade-in-up">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.announcements.index') }}" class="p-2 bg-white rounded-full text-slate-500 hover:text-blue-900 shadow-sm border border-slate-100 transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-bold font-['Plus_Jakarta_Sans'] text-blue-950 dark:text-white">Buat Informasi</h1>
            <p class="text-slate-500 mt-1 dark:text-slate-400">Tambahkan pengumuman atau peraturan baru.</p>
        </div>
    </div>

    @if ($errors->any())
    <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm">
        <div class="text-red-700 font-medium">
            Terdapat beberapa kesalahan:
            <ul class="list-disc ml-5 mt-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 md:p-8">
        <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data" id="announcementForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Judul</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors bg-slate-50 focus:bg-white" placeholder="Contoh: Jadwal Latihan Khusus...">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Jenis Informasi</label>
                    <select name="type" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors bg-slate-50 focus:bg-white">
                        <option value="pengumuman" {{ old('type') == 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                        <option value="peraturan" {{ old('type') == 'peraturan' ? 'selected' : '' }}>Peraturan</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Isi Informasi</label>
                <!-- Editor Container -->
                <div id="editor-container" class="h-64 bg-slate-50 rounded-b-xl border-slate-200"></div>
                <!-- Hidden Input -->
                <input type="hidden" name="content" id="content" value="{{ old('content') }}">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Lampiran File (Opsional)</label>
                <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer relative" id="fileUploadContainer">
                    <input type="file" name="attachment" id="attachment" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".pdf,.doc,.docx">
                    <span class="material-symbols-outlined text-4xl text-slate-400 mb-2">upload_file</span>
                    <p class="text-slate-600 font-medium">Klik untuk mengunggah file (PDF, DOCX)</p>
                    <p class="text-slate-400 text-sm mt-1">Maksimal ukuran: 10MB</p>
                    <div id="fileName" class="mt-3 text-blue-600 font-bold hidden"></div>
                </div>
            </div>

            <div class="mb-8">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500" {{ old('is_active', true) ? 'checked' : '' }}>
                    <span class="text-slate-700 font-medium">Aktifkan & Publisir (Tampilkan di member)</span>
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.announcements.index') }}" class="px-6 py-2.5 bg-white border border-slate-300 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-900 border border-transparent rounded-xl text-sm font-bold text-white hover:bg-blue-800 transition-colors shadow-sm focus:outline-none flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">save</span>
                    Simpan Informasi
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        placeholder: 'Tuliskan informasi di sini...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'clean']
            ]
        }
    });

    // Populate old content if validation fails
    var oldContent = document.getElementById('content').value;
    if(oldContent) {
        quill.clipboard.dangerouslyPasteHTML(oldContent);
    }

    document.getElementById('announcementForm').onsubmit = function() {
        var content = document.querySelector('input[name=content]');
        content.value = quill.root.innerHTML;
        if(quill.getText().trim().length === 0) {
            content.value = '';
        }
    };

    // File Name display
    document.getElementById('attachment').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : '';
        var fileNameDisplay = document.getElementById('fileName');
        if(fileName) {
            fileNameDisplay.textContent = 'File Terpilih: ' + fileName;
            fileNameDisplay.classList.remove('hidden');
        } else {
            fileNameDisplay.classList.add('hidden');
        }
    });
</script>
@endsection
