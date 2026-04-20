@extends('layouts.app')

@section('content')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<div class="px-6 py-8 md:px-12 md:py-10 animate-fade-in-up">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.announcements.index') }}" class="p-2 bg-white rounded-full text-slate-500 hover:text-blue-900 shadow-sm border border-slate-100 transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-bold font-['Plus_Jakarta_Sans'] text-blue-950 dark:text-white">Edit Informasi</h1>
            <p class="text-slate-500 mt-1 dark:text-slate-400">Ubah detail pengumuman atau peraturan.</p>
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
        <form action="{{ route('admin.announcements.update', $announcement->id) }}" method="POST" enctype="multipart/form-data" id="announcementForm">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Judul</label>
                    <input type="text" name="title" value="{{ old('title', $announcement->title) }}" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors bg-slate-50 focus:bg-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Jenis Informasi</label>
                    <select name="type" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors bg-slate-50 focus:bg-white">
                        <option value="pengumuman" {{ old('type', $announcement->type) == 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                        <option value="peraturan" {{ old('type', $announcement->type) == 'peraturan' ? 'selected' : '' }}>Peraturan</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Isi Informasi</label>
                <div id="editor-container" class="h-64 bg-slate-50 rounded-b-xl border-slate-200"></div>
                <!-- Hidden Input -->
                <input type="hidden" name="content" id="content" value="{{ old('content', $announcement->content) }}">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Lampiran File (Opsional)</label>
                @if($announcement->attachment_name)
                    <div class="mb-3 flex items-center justify-between p-3 bg-blue-50 text-blue-700 rounded-lg border border-blue-100 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined">attach_file</span>
                            <span>File Terpasang: <strong>{{ $announcement->attachment_name }}</strong></span>
                        </div>
                        <button type="button" onclick="if(confirm('Hapus lampiran ini?')) { document.getElementById('delete-attachment-form').submit(); }" class="flex items-center gap-1 text-red-500 hover:text-red-700 font-bold transition-colors">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                            Hapus File
                        </button>
                    </div>
                @endif
                
                <form id="delete-attachment-form" action="{{ route('admin.announcements.deleteAttachment', $announcement->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
                <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer relative" id="fileUploadContainer">
                    <input type="file" name="attachment" id="attachment" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".pdf,.doc,.docx">
                    <span class="material-symbols-outlined text-4xl text-slate-400 mb-2">upload_file</span>
                    <p class="text-slate-600 font-medium">{{ $announcement->attachment_name ? 'Klik untuk mengganti file lampiran' : 'Klik untuk mengunggah file (PDF, DOCX)' }}</p>
                    <p class="text-slate-400 text-sm mt-1">Maksimal ukuran: 10MB</p>
                    <div id="fileName" class="mt-3 text-blue-600 font-bold hidden"></div>
                </div>
            </div>

            <div class="mb-8">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500" {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}>
                    <span class="text-slate-700 font-medium">Aktifkan & Publisir (Tampilkan di member)</span>
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.announcements.index') }}" class="px-6 py-2.5 bg-white border border-slate-300 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-900 border border-transparent rounded-xl text-sm font-bold text-white hover:bg-blue-800 transition-colors shadow-sm focus:outline-none flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">save</span>
                    Simpan Perubahan
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

    // Populate old content
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
            fileNameDisplay.textContent = 'File Baru Terpilih: ' + fileName;
            fileNameDisplay.classList.remove('hidden');
        } else {
            fileNameDisplay.classList.add('hidden');
        }
    });
</script>
@endsection
