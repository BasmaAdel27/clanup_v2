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
            <div class="form-group mb-4">
                <label class="form-label" for="featured_image">{{ __('Featured Image') }}</label>
                @if ($blog->featured_image)
                    <p>
                        <img class="img-thumbnail h-110px" src="{{ $blog->image }}">
                    </p>
                @endif
                <input class="form-control" name="featured_image" type="file" accept="image/png, image/jpeg">
            </div>

            <div class="form-group mb-4 required">
                <label class="mb-1" for="blog_category">{{ __('Blog Category') }}</label>
                <select name="blog_category_id" class="form-control">
                    <option value="">{{ __('Please select') }}</option>
                    @foreach(get_all_blog_categories() as $option)
                        <option value="{{ $option->id }}" {{ $blog->blog_category_id == $option->id ? 'selected=""' : '' }}>{{ $option->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-4 required">
                <label class="mb-1" for="name">{{ __('Name') }}</label>
                <input name="name" type="text" class="form-control" placeholder="{{ __('Name') }}" value="{{ $blog->name }}" required>
            </div>

            @if ($blog->slug)
                <div class="form-group mb-4 required">
                    <label class="mb-1" for="slug">{{ __('Slug') }}</label>
                    <input name="slug" type="text" class="form-control" placeholder="{{ __('Slug') }}" value="{{ $blog->slug }}">
                </div>
            @endif

            <div class="form-group mb-4 required">
                <label class="mb-1" for="name">{{ __('Description') }}</label>
                <input name="description" type="text" class="form-control" placeholder="{{ __('Description') }}" value="{{ $blog->description }}" required>
            </div>

            <div class="form-group mb-4 required">
                <label class="mb-1" for="content">{{ __('Content') }}</label>
                <textarea name="content" class="form-control tinymce">{{ $blog->content }}</textarea>
            </div>
            
            <div class="text-end">
                @if ($blog->slug)
                    <a class="btn btn-outline-danger delete-confirm" href="{{ route('admin.blogs.delete', $blog->slug) }}" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This record will be deleted.') }}">{{ __('Delete') }}</a>
                @endif
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>
