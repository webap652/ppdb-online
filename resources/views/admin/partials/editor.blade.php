{{-- TinyMCE (versi gratis / GPL, self-hosted via CDN) dengan toolbar minimalis --}}
@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tinymce@7.6.0/tinymce.min.js" referrerpolicy="origin"></script>
        <script>
            tinymce.init({
                selector: 'textarea.editor',
                license_key: 'gpl',
                menubar: false,
                branding: false,
                promotion: false,
                statusbar: false,
                plugins: 'autoresize link image media lists',
                toolbar: 'blocks | bold italic | bullist numlist | link image media | removeformat',
                block_formats: 'Paragraf=p; Judul 2=h2; Judul 3=h3',
                min_height: 420,
                autoresize_bottom_margin: 20,
                convert_urls: false,
                link_default_target: '_blank',
                link_assume_external_targets: true,
                image_caption: true,
                image_dimensions: false,
                media_live_embeds: true,
                media_dimensions: false,
                automatic_uploads: true,
                images_file_types: 'jpg,jpeg,png,gif,webp',
                images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
                    const form = new FormData();
                    form.append('file', blobInfo.blob(), blobInfo.filename());

                    fetch(@json(route('admin.editor.upload')), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: form,
                    })
                        .then(async (res) => {
                            const json = await res.json().catch(() => ({}));
                            if (!res.ok) throw new Error(json.message || 'Gagal mengunggah gambar.');
                            resolve(json.location);
                        })
                        .catch((err) => reject({ message: err.message, remove: true }));
                }),
                content_style: `
                    body { font-family: Inter, system-ui, sans-serif; font-size: 15px; line-height: 1.7; color: #1e293b; max-width: 760px; margin: 1rem auto; padding: 0 1rem; }
                    img, iframe, video { max-width: 100%; height: auto; border-radius: 6px; }
                    iframe { width: 100%; aspect-ratio: 16 / 9; }
                    figure.image { margin: 1rem 0; } figure.image figcaption { font-size: 13px; color: #64748b; text-align: center; }
                `,
            });
        </script>
    @endpush
@endonce
