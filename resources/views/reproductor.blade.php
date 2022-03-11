<div>
        <div class="reproductor-iconos" style="margin-top: 3rem">
            <i class="fa fa-play" aria-hidden="true" onclick="resume()"></i>
            <i class="fa fa-pause" aria-hidden="true" onclick="pausa()"></i>
            <i class="fa fa-stop" aria-hidden="true" onclick="stop()"></i>
        </div>
        <div class="reproductor-capitulos">
            @foreach ($capitulos as $item)
                <div class="reproductor-lista">
                    <h4 class="reproductor-lista-titulo">{{ $item->titulo }} {{ $item->numero }}</h4>
                    <div>
                        @if ($item->marcado == true)
                            <i class="fa fa-bookmark" aria-hidden="true"></i>
                        @endif
                        <i class="fa fa-play" aria-hidden="true" onclick="play({{ $item->id }})"></i>
                    </div>
                </div>
            @endforeach
        </div>
    
</div>


