
@if($totalPages > 1)
    @if(isset($extraParams['url']))
        @foreach($extraParams['url'] as $key => $value)
                @php
                    $params[$key] = $value;
                @endphp
        @endforeach
    @endif 
    <div class="pagination-area">
            <ul>
                @if($page > 1)
                    @php
                        $params['page'] = $page - 1;
                    @endphp
                    <li>
                        <a href="{{ route( $nameRoute, $params) }}{{ $extraParams['getData']?'?'.http_build_query($extraParams['getData']):'' }}" aria-label="Anterior">
                            <i class="fa fa-angle-left"></i>
                        </a>
                    </li>
                @endif
                @for($x = 1; $x <= $totalPages; $x++)
                        @php
                            $params['page'] = $x;
                        @endphp
                        <li><a @if($x == $page) class="active" @endif href="{{ route( $nameRoute, $params) }}{{ $extraParams['getData']?'?'.http_build_query($extraParams['getData']):'' }}" >{{$x}}</a></li>

                @endfor


                @if($page != $totalPages)
                        @php
                            $params['page'] = $page + 1;
                        @endphp
                    <li>
                        <a href="{{ route( $nameRoute, $params) }}{{ $extraParams['getData']?'?'.http_build_query($extraParams['getData']):'' }}" aria-label="Siguiente">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </li>
                @endif
            </ul>
    </div>

@endif
