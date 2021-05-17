<!--================================================== -->

<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
<script data-pace-options='{ "restartOnRequestAfter": true }' src="js/plugin/pace/pace.min.js"></script>

<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script>
        if (!window.jQuery) {
                document.write('<script src="js/libs/jquery-2.0.2.min.js"><\/script>');
        }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script>
        if (!window.jQuery.ui) {
                document.write('<script src="js/libs/jquery-ui-1.10.3.min.js"><\/script>');
        }
</script>

<!-- Datepicker UI Español -->
<script src="js/libs/jquery.ui.datepicker-es.js"></script>

<!-- JS TOUCH : include this plugin for mobile drag / drop touch events
<script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

<!-- BOOTSTRAP JS -->
<script src="js/bootstrap/bootstrap.min.js"></script>

<!-- CUSTOM NOTIFICATION -->
<script src="js/notification/SmartNotification.min.js"></script>

<!-- JARVIS WIDGETS -->
<script src="js/smartwidgets/jarvis.widget.min.js"></script>

<!-- EASY PIE CHARTS -->
<!--<script src="js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>-->

<!-- SPARKLINES -->
<!-- <script src="js/plugin/sparkline/jquery.sparkline.min.js"></script> -->

<!-- JQUERY VALIDATE -->
<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
<script src="js/plugin/jquery-validate/jquery.validate.custom.messages.js"></script>

<!-- JQUERY MASKED INPUT -->
<!-- <script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script> -->

<!-- JQUERY UI + Bootstrap Slider -->
<!-- <script src="js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script> -->

<!-- browser msie issue fix -->
<script src="js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

<!-- FastClick: For mobile devices -->
<script src="js/plugin/fastclick/fastclick.js"></script>

<!--[if IE 7]>

<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

<![endif]-->

<!-- Demo purpose only -->
<!--<script src="js/demo.js"></script>-->

<!-- MAIN APP JS FILE -->
<script src="js/app.js"></script>
<script src="js/appCustom.js"></script>
<script src="js/appCustomUser.js"></script>

<!-- Text Editor -->
<script src="js/plugin/summernote/summernote.js"></script>

<!-- Drag & Drop-->
<script src="js/plugin/dropzone/dropzone.min.js"></script>

<!--Context Menu-->
<script src="//cdn.jsdelivr.net/jquery.ui-contextmenu/1.7.0/jquery.ui-contextmenu.min.js"></script>
<!--Datatables-->
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

<script src="js/plugin/datatables/jquery.dataTables.js"></script>
<script src="js/plugin/datatables/jquery.dataTables-cust.min.js"></script>
<script src="js/plugin/datatables/fnStandingRedraw.js"></script>
<script src="js/plugin/datatables/dataTables.editor.min.js"></script>
<script src="js/plugin/datatables/fixedHeader.js"></script>
<script src="js/plugin/datatables/jquery.dataTables.language.js"></script>
<script src="js/plugin/datatables/ColReorder.min.js"></script>
<script src="js/plugin/datatables/FixedColumns.min.js"></script>
<script src="js/plugin/datatables/ColVis.min.js"></script>
<script src="js/plugin/datatables/ZeroClipboard.js"></script>
<script src="js/plugin/datatables/DT_bootstrap.js"></script> 

<script src="js/plugin/moment/moment.min.js"></script>

@if(Request::is('clientes') or Request::is('ptosVta'))
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5DLwPPVAz88_k0yO2nmFe7T9k1urQs84&language=es-AR&v=3.exp&libraries=places"></script>
@endif

<!-- <script src="js/plugin/bootstrap-timepicker/bootstrap-datepicker.js"></script>
<script src="js/plugin/bootstrap-timepicker/bootstrap-datepicker.es.js"></script> -->

<script src="js/plugin/fontawesome-iconpicker/fontawesome-iconpicker.min.js"></script>

<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.0.3/css/dataTables.checkboxes.css" rel="stylesheet" />
<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.0.3/js/dataTables.checkboxes.min.js"></script>
 
<!-- JQUERY SELECT2 INPUT -->
 <script src="js/plugin/select2/select2.full.min.js"></script>

 <!-- clockpicker -->
 <script src={{asset("js/plugin/clockpicker/clockpicker.js")}}></script>

 <!-- Libreria espa�ol -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/es.js"></script>
 <!-- Include external JS libs. FROALA -->
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.js"></script>
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/mode/xml/xml.min.js"></script>

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

$.fn.select2.defaults.set('language', 'es');
$.fn.select2.defaults.set('width', 'resolve');

</script>