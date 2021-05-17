@extends('layouts.base')


@section('main_container')

		
<div class="row">
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-8">
            <h1 class="page-title txt-color-blueDark">
                    <i class="fa fa-cog"></i> 
                            {{ $aViewData['resourceLabel'] }}
            </h1>
    </div>
</div>
				
                            
<!-- widget grid -->
<section id="widget-grid" class="">

        <!-- row -->
        <div class="row">

                <!-- NEW WIDGET START -->
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <!-- Widget ID (each widget will need unique ID)-->
                        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false" data-widget-deletebutton="true">
                                <header>
                                        <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                                        <h2></h2>

                                </header>

                                <!-- widget div-->
                                <div>

                                        <!-- widget edit box -->
                                        <div class="jarviswidget-editbox">
                                                <!-- This area used as dropdown edit box -->

                                        </div>
                                        <!-- end widget edit box -->
                                
                                       <div class="widget-body  col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                               
                                                
                                        @foreach($aViewData['ratiosG'] as $ra)
                                                
                                             
                                        <div class="well no-padding">
                                              
                                                 <div class="bar-holder col-lg-6">
                                                    <h4><b>{!!$ra->nombre !!}</b></h4><br> 
                                                     <h5>Enviados: {!! $ra->enviados !!} </h5>
                                                     <h5>Asunto: {!!$ra->asunto !!}</h5>
                                                     <h5>Clicks Totales: <?php echo App\AppCustom\Util::clicksTotalsSimple($ra->campaign_id); ?></h5><br>
                                                    <h4><b>{!!$ra->ratio !!} % </b></h4>
                                                     <div class="progress">
                                                       <div class="progress-bar bg-color-teal" data-transitiongoal="{!!$ra->ratio !!}" aria-valuenow="{!!$ra->ratio !!}" style="width: {!!$ra->ratio !!}%;">{!!$ra->ratio !!}%</div>
                                                     </div>
                                                 </div>       

                                                 <?php $mails = App\AppCustom\Util::mails($ra->campaign_id); ?>

                                                     <div class="table-responsive">
                                             
                                                             <table class="table table-bordered">
                                                                     <thead>
                                                                             <tr>
                                                                                    <th>Emails</th>
                                                                                     <th>Nombre</th>
                                                                                     <th>Apellido</th>                                                                                    
                                                                                     <th>Aperturas</th>
                                                                                     <th>Clicks</th>
                                                                             </tr>
                                                                     </thead>
                                                                     <tbody>
                                                                        @foreach($mails as $k=>$m)
                                                                                <tr>
                                                                                        <?php $clicks=App\AppCustom\Util::clicksByNumberSimple($ra->campaign_id,$m->id); ?>
                                                                                        <td>{!!$m->mail !!}</td>
                                                                                        <td>{!!$m->nombre !!}</td>
                                                                                        <td>{!!$m->apellido !!}</td>
                                                                                        <th>{!!$m->c !!}</th>
                                                                                        <td>@if($clicks!=0)<a href="#" data-toggle="modal" data-target="#myModal{{$k}}">{!! $clicks !!}</a>@else 0 @endif</td>
                                                                                </tr>
                                                                                <!-- Modal -->
                                                                                <div class="modal fade" id="myModal{{$k}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                                        <div class="modal-dialog" role="document">
                                                                                        <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                        <h4 class="modal-title" id="myModalLabel">Detalle Clicks</h4>
                                                                                        </div>
                                                                                                <div class="modal-body">                                                                                                        

                                                                                                                <?php $clicksA = App\AppCustom\Util::clicksByMailSimple($ra->campaign_id,$m->id); ?>
                                                                                                                        @foreach($clicksA as $k=>$m)
                                                                                                                         <div class="row">
                                                                                                                                        <hr />
                                                                                                                                        <div class="col col-sm-12"><strong>Link: </strong>{!!$m->link !!} </div>                                                                                                                                        
                                                                                                                                        <div class="col col-sm-12"><strong>Clicks: </strong>{!!$m->clicks !!}</div>
                                                                                                                                        <hr />
                                                                                                                        </div>                                                                                                                                                                                                                                                     
                                                                                                                        @endforeach
                                                                                                                                
                                                                                                </div>
                                                                                        <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                                                        </div>
                                                                                        </div>
                                                                                        </div>
                                                                                </div>
                                                                        @endforeach   
                                                                     </tbody>
                                                             </table>
                                                     
                                                     </div>  
                                             </div> <br><br>   
                                        @endforeach
                                                
                                        </div>
                                        <!-- end widget content -->
                                </div>
                       
                                <!-- end widget div -->

                        </div>
                        <!-- end widget -->


                </article>
                <!-- WIDGET END -->

        </div>

        <!-- end row -->

</section>
<!-- end widget grid -->

@stop

@section('custom_scripts_container')
<script src={{asset("js/appCustom_".$aViewData['resource'].".js")}} ></script>

<script src={{asset("js/plugin/cropit/jquery.cropit.js")}}></script>
@stop
