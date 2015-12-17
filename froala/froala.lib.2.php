  <!-- Include JS files. -->
  <script src="<?=$g4[path]?>/froala/js/froala_editor.min.js?v=<?=$g4[froala_ver]?>"></script>

  <script type="text/javascript">
      $.FroalaEditor.DEFAULTS.key = '<?=$g4[froala_key]?>';
  </script>

  <!-- Include Plugins. -->
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/align.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/char_counter.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/code_view.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/colors.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/emoticons.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/entities.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/file.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/font_family.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/font_size.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/fullscreen.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/image.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/image_manager.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/inline_style.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/line_breaker.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/link.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/lists.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/paragraph_format.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/paragraph_style.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/quote.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/table.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/save.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/url.min.js?v=<?=$g4[froala_ver]?>"></script>
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/plugins/video.min.js?v=<?=$g4[froala_ver]?>"></script>

  <link href="<?=$g4[path]?>/froala/css/themes/gray.min.css?v=<?=$g4[froala_ver]?>" rel="stylesheet" type="text/css" />

  <!-- Include Language file if we'll use it. -->
  <script type="text/javascript" src="<?=$g4[path]?>/froala/js/languages/ko.js?v=<?=$g4[froala_ver]?>"></script>

  <!-- Initialize the editor. -->
  <script type="text/javascript">
  $(function() {
    $('#wr_content').froalaEditor({
      theme: 'gray',
      language: 'ko',     // https://www.froala.com/wysiwyg-editor/docs/examples/language
      fontFamily: {       // https://www.froala.com/wysiwyg-editor/docs/examples/font-family
        "¸¼Àº °íµñ": '¸¼Àº °íµñ',
        "±¼¸²": '±¼¸²',
        "Malgun Gothic": 'Malgun Gothic',
        "gulim": 'gulim'
      },
      heightMin: 200,     // https://www.froala.com/wysiwyg-editor/docs/examples/adjustable-height
      heightMax: 300,

      toolbarButtons :  ['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', '|', 'color', 'emoticons', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', 'insertHR', 'insertLink', 'insertImage', 'insertVideo', 'insertTable', 'undo', 'redo', 'clearFormatting', 'selectAll', 'html'],
      toolbarButtonsMD: ['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', '|', 'color', 'emoticons', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', 'insertHR', 'insertLink', 'insertImage', 'insertVideo', 'insertTable', 'undo', 'redo', 'clearFormatting', 'selectAll', 'html'],
      toolbarButtonsSM: ['fullscreen', 'bold', 'italic', 'underline', 'fontFamily', 'fontSize', 'color', 'emoticons', 'insertLink', 'insertImage', 'insertVideo', 'insertTable', 'clearFormatting', 'undo', 'redo'],
      toolbarButtonsXS: ['bold', 'italic', 'fontFamily', 'fontSize', 'insertLink', 'insertImage', 'insertVideo','clearFormatting', 'undo', 'redo'],

      // Set the image upload parameter.
      imageUploadParam: 'file',

      // Set the image upload URL.
      imageUploadURL: '<?=$g4[path]?>/froala/image_upload.php',

      // Additional upload params (bo_table °ªÀ» image_upload.php¿¡ ³Ñ±ä´Ù)
      imageUploadParams: {bo_table: '<?=$bo_table?>'},

      // Set request type.
      imageUploadMethod: 'POST',

      // Set max image size to 20MB.
      imageMaxSize: 20 * 1024 * 1024,

      // Allow to upload PNG and JPG. GIF
      imageAllowedTypes: ['jpeg', 'jpg', 'png', 'gif'],

      // image alignment
      imageDefaultAlign: 'left',

      // image width
      imageDefaultWidth: 0,

      // Set the file upload URL.
      fileUploadURL: '<?=$g4[path]?>/froala/file_upload.php',

      fileAllowedTypes: ['text/plain', 'application/pdf', 'application/x-pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.hancom.hwp', 'application/x-hwp', 'application/haansofthwp', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint'],

      fileMaxSize: 10* 1024 * 1024,

      fileUploadParams: {bo_table: '<?=$bo_table?>'}

    })

    .on('froalaEditor.image.error', function (e, editor, error, response) {
        console.log(error.code);
    })

    .on('froalaEditor.file.error', function (e, editor, error, response) {
        console.log(error.code);
    })

    // Catch image removal from the editor.
    .on('froalaEditor.image.removed', function (e, editor, $img) {
        $.ajax({

          // Request method.
          method: "POST",

          // Request URL.
          url: "/froala/image_delete.php",

          // Request params.
          data: {
            src: $img.attr('src')
          }
        })
        .done (function (data) {
          console.log ('image was deleted');
        })
        .fail (function () {
          console.log ('image delete problem');
        })
        
        });
  });
  </script>