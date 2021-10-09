<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/lang/summernote-es-ES.js"></script>

<script>
    $(document).ready(function(){
        $('.summernote').summernote({
            lang: 'es-ES',
            height: 400,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'italic','clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
        });
    });
</script>