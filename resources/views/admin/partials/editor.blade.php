@push('admin-styles')
<style>
    .ck-editor__editable_inline { min-height: 220px; }
</style>
@endpush

@push('admin-scripts')
<script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
<script>
    (function () {
        var field = document.querySelector('#{{ $field }}');

        if (! field || typeof ClassicEditor === 'undefined') {
            return;
        }

        ClassicEditor
            .create(field, {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'],
            })
            .catch(function (error) { console.error(error); });
    })();
</script>
@endpush
