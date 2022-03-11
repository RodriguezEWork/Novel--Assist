<div>
    <aside class="reproductor-novel">
        <h2>Super Security in The City</h2>
        {{-- <input wire:model="dato" type="search">Agregar novela</input> --}}
        <div class="reproductor-iconos">
            <i class="fa fa-play" aria-hidden="true"></i>
            <i class="fa fa-pause" aria-hidden="true"></i>
            <i class="fa fa-stop" aria-hidden="true"></i>
        </div>
        <div class="reproductor-capitulos">
            @foreach ($capitulos as $item)
                <div class="reproductor-lista">
                    <h4 class="reproductor-lista-titulo">{{ $item->numero }} | {{ $item->titulo }} </h4>
                    <i class="fa fa-play" aria-hidden="true"></i>
                </div>
            @endforeach
        </div>

    </aside>
</div>