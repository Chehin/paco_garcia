<div class="socials-box">
	<a href="https://www.facebook.com/sharer/sharer.php?u={{app('url')->full()}}" target="_blank"><i class="fa fa-facebook"></i></a> 
	<a href="https://twitter.com/home?status={{$pageTitle}} {{app('url')->full()}}" target="_blank"><i class="fa fa-twitter"></i></a> 
	<a href="https://plus.google.com/share?url={{app('url')->full()}}" target="_blank"><i class="fa fa-google-plus"></i></a>
	<a href="https://api.whatsapp.com/send?text={{ $pageTitle.' '.app('url')->full()}}" target="_blank"><i class="fa fa-whatsapp"></i></a>
	<a href="mailto:?subject={{$pageTitle}}&amp;body={{app('url')->full()}}"><i class="fa fa-envelope"></i></a>
</div>