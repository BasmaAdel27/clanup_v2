@push('page_head_scripts')
    <script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: 'textarea.tinymce',
            menubar: false,
            plugins: [
                "advlist autolink lists link image charmap print preview anchor paste",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste imagetools"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image ",
            image_title: true,
            paste_auto_cleanup_on_paste : true,
            paste_remove_styles: true,
            paste_remove_styles_if_webkit: true,
            paste_strip_class_attributes: true,
            automatic_uploads: true,
            images_upload_url: '/admin/tinymce/upload',
            file_picker_types: 'image',
            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.onchange = function() {
                    var file = this.files[0];

                    var reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = function () {
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);
                        cb(blobInfo.blobUri(), { title: file.name });
                    };
                };
                input.click();
            }
        });
    </script>
@endpush

<div class="card p-3 mb-4">
    <div class="row">
        <div class="col-12 card-body">
            <div class="form-group mb-4 required">
                <label class="mb-1" for="name">{{ __('Name') }}</label>
                <input name="name" type="text" class="form-control" placeholder="{{ __('Name') }}" value="{{ $page->name }}" required>
            </div>

            @if ($page->slug and $page->is_deletable)
                <div class="form-group mb-4 required">
                    <label class="mb-1" for="slug">{{ __('Slug') }}</label>
                    <input name="slug" type="text" class="form-control" placeholder="{{ __('Slug') }}" value="{{ $page->slug }}" required>
                </div>
            @endif

            <div class="form-group mb-4 required">
                <label class="mb-1" for="name">{{ __('Description') }}</label>
                <input name="description" type="text" class="form-control" placeholder="{{ __('Description') }}" value="{{ $page->description }}" required>
            </div>

            <div class="form-group mb-4 required">
                <label class="mb-1" for="content">{{ __('Content') }}</label>
                <textarea name="content" class="form-control tinymce">{{ $page->content }}</textarea>
            </div>

            <div class="form-group mb-4">
                <label for="is_active">{{ __('Active') }}</label>
                <select name="is_active" class="form-control">
                    <option value="0" {{ $page->is_active == false ? 'selected' : '' }}>{{ __('Disabled') }}</option>
                    <option value="1" {{ $page->is_active == true  ? 'selected' : '' }}>{{ __('Active') }}</option>
                </select>
            </div>

            <div class="form-group mb-4">
                <label for="show_on_footer">{{ __('Show on footer') }}</label>
                <select name="show_on_footer" class="form-control">
                    <option value="0" {{ $page->show_on_footer == false ? 'selected' : '' }}>{{ __('No') }}</option>
                    <option value="1" {{ $page->show_on_footer == true  ? 'selected' : '' }}>{{ __('Yes') }}</option>
                </select>
            </div>
            
            <div class="text-end">
                @if ($page->is_deletable)
                    <a class="btn btn-outline-danger delete-confirm" href="{{ route('admin.pages.delete', $page->slug) }}" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This record will be deleted.') }}">{{ __('Delete') }}</a>
                @endif
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>
