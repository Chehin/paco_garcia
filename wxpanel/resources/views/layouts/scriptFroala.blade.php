<!-- Include Editor JS files. FROALA-->
<script type="text/javascript" src={{asset("js/plugin/froala_editor_2.6.4/js/froala_editor.pkgd.min.js")}}></script>
<script type="text/javascript" src={{asset("js/plugin/froala_editor_2.6.4/js/languages/es.js")}}></script>
<script type="text/javascript" src={{asset("js/plugin/froala_editor_2.6.4/js/plugins/image_manager.min.js")}}></script>
<script type="text/javascript" src={{asset("js/plugin/froala_editor_2.6.4/js/third_party/image_aviary.min.js")}}></script>
<script type="text/javascript" src={{asset("js/plugin/froala_editor_2.6.4/js/plugins/code_view.min.js")}}></script>
<script type="text/javascript" src={{asset("js/plugin/froala_editor_2.6.4/js/plugins/special_characters.min.js")}}></script>
<script type="text/javascript" src={{asset("js/plugin/froala_editor_2.8.1/js/plugins/table.min.js")}}></script>
<script type="text/javascript" src={{asset("js/plugin/froala_editor_2.8.1/js/plugins/colors.min.js")}}></script>
<script type="text/javascript" src={{asset("js/plugin/froala_editor_2.6.4/js/plugins/font_family.min.js")}}></script>
<script> 
    $('textarea#content').froalaEditor({
     heightMin: 500,
     language: 'es',
     zIndex: 8000,
     placeholderText: '',
     toolbarSticky: true,
     fontFamily: {
       "Roboto,sans-serif": 'Roboto',
       "Oswald,sans-serif": 'Oswald',
       "Montserrat,sans-serif": 'Montserrat',
       "'Open Sans Condensed',sans-serif": 'Open Sans Condensed'
     },
     fontFamilySelection: true,
     fontSizeSelection: true,
     colorsHEXInput: true,
     toolbarButtons: ['bold', 'italic', 'underline', 'fontFamily', 'fontSize', '|', 'color', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'quote', 'insertHR', 'undo', 'redo', 'html', '|', 'spellChecker', '|', 'insert'],
     colorsBackground: ['#61BD6D', '#1ABC9C', '#54ACD2', '#2C82C9', '#9365B8', '#475577', '#CCCCCC',
    '#41A85F', '#00A885', '#3D8EB9', '#2969B0', '#553982', '#28324E', '#000000',
    '#F7DA64', '#FBA026', '#EB6B56', '#E25041', '#A38F84', '#EFEFEF', '#FFFFFF',
    '#FAC51C', '#F37934', '#D14841', '#B8312F', '#7C706B', '#D1D5D8', '#3B7672', '#94AC31', 'REMOVE'],
    colorsText: ['#61BD6D', '#1ABC9C', '#54ACD2', '#2C82C9', '#9365B8', '#475577', '#CCCCCC',
    '#41A85F', '#00A885', '#3D8EB9', '#2969B0', '#553982', '#28324E', '#000000',
    '#F7DA64', '#FBA026', '#EB6B56', '#E25041', '#A38F84', '#EFEFEF', '#FFFFFF',
    '#FAC51C', '#F37934', '#D14841', '#B8312F', '#7C706B', '#D1D5D8', '#3B7672', '#94AC31', 'REMOVE'], 
    // Establece la URL de carga de fotos.
     imageUploadURL: '{!!URL::to('froalaImage') !!}',
     // Establece la URL de carga del archivo.
     fileUploadURL: '{!!URL::to('froalaFile') !!}',
     imageAllowedTypes: ['jpeg', 'jpg', 'png'],
     imageUploadParams: {_token: $("[name='_token']").val()},
     aviaryKey: 'b0c1e5af4b074d4e85b9f82ee32be2b2',
     htmlUntouched : true
    }).on('froalaEditor.contentChanged', function (e, editor) {
        $('#fill').val(editor.html.get());
    });
</script>