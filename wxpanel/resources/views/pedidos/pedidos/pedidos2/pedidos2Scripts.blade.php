@extends('pedidos/pedidos/pedidosScripts')
<script>
	$(function(){
		//$('.dataTables_length2').css('display','none')
		setTimeout(function(){ $('.dataTables_length2.filtro-all').css('display','none');$('.widget-body-toolbar').css('height', ''); }, 500);
	});
</script>