@extends('Layouts.app')

@section ('titulo', 'inicio')

@section ('contenido')

    <div class="General-Novel">
        <main class="Listado-novelas">
            <div class="contenedor-cartas">
                <div class="titulares">
                    <h2>Novelas</h2>
                    <h3 id="registroCapitulo"></h3>
                    <a href="#modal2" value="2" class="cna">Subir Capitulo</a>
                    <a href="#modal" value="2" class="cna">Agregar novela</a>
                </div>
                <input type="search" id="search" placeholder="Buscar Novelas" class="field-style field-full align-none search">
                <div class="cartas" id="cartas">
                </div>
            </div>
            <div id="modal" class="modal">
                <div class="modal_container">
                    <form id="crear-form" class="form-style-9">
                        @csrf
                        <ul>
                            <li>
                                <input id="titulo" type="text" name="field3" class="field-style field-full align-none" placeholder="Titulo" />
                            </li>
                            <li>
                                <input id="slug" type="text" name="field3" class="field-style field-full align-none" placeholder="Slug" />
                            </li>
                            <li>
                            <input id="linkImage" type="text" name="field3" class="field-style field-full align-none" placeholder="linkImage" />
                            </li>
                            <li>
                            <textarea id="discripcion" name="field5" class="field-style" placeholder="Descripcion"></textarea>
                            </li>
                            <li>
                            <input type="submit" value="Subir novela" />
                            <a href="#general">Cerrar</a>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>

            <div id="modal2" class="modal">
                <div class="modal_container">
                    <form id="subir-form" class="form-style-9">
                        @csrf
                        <ul>
                            <li>
                                <input id="tituloCap" type="text" name="field3" class="field-style field-full align-none" placeholder="Titulo" />
                            </li>
                            <li>
                                <input id="numero" type="text" name="field3" class="field-style field-full align-none" placeholder="Numero" />
                            </li>
                            <li>
                                <select name="Selector_Novelas" id="id_Novelas" class="field-style field-full align-none">
                                    <option value="">Uno</option>
                                </select>
                            </li>
                            <li>
                            <textarea id="capitulo" name="field5" class="field-style" placeholder="Capitulo"></textarea>
                            </li>
                            <li>
                            <input type="submit" value="Subir Capitulo" />
                            <a href="#general">Cerrar</a>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>

        </main>

        <aside class="reproductor-novel">

        </aside>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>

        var comprobante = false;

        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function cargarCapitulos($id) {
            fetch('/enlistar?q='+$id)
                .then(res => res.text())
                .then(html => {
                    document.querySelector('.reproductor-novel').innerHTML = html
                });
        }

        function play ($id) {

                var id = $id;

                stop();
                speaking($id);
        };

        function speaking ($id) {

            const id = { 
            id : $id
            };

            $.post('/novelas/capitulo', id, function (response) {
                let capitulo = JSON.parse(response);
                var voicesAvailable = speechSynthesis.getVoices();

                // Create the utterance object setting the chosen parameters
                var utterance = new SpeechSynthesisUtterance();
                utterance.text = capitulo.titulo + capitulo.numero +  'contenido: \n\n\n' + capitulo.capitulo;
                utterance.voice = voicesAvailable[81];
                utterance.lang = voicesAvailable[81];
                utterance.rate = 1.4;
                utterance.pitch = 1;

                utterance.onstart = function() {
                    comprobante = false;
                    let idcapitulo = capitulo.numero;
                    let idNovela = capitulo.id_Novelas;

                    registro (idcapitulo);
                    cargarCapitulos (idNovela);
                };
                utterance.onend = function() {
                    if (!comprobante){
                        let idcapitulo = capitulo.id;
                        let idmarcado = capitulo.id;

                        idcapitulo--;

                        marcado (idmarcado);
                        speaking(idcapitulo);
                    }
                };


                window.speechSynthesis.speak(utterance);
            })


        }

        function pausa () {
                window.speechSynthesis.pause();
            };

        function resume () {
            window.speechSynthesis.resume();
        };

        function stop () {
            window.speechSynthesis.cancel();
            comprobante = true;
        };

        function registro ($capitulo) {

            let capituloId = $capitulo;

            let registroNumerico = document.querySelector('#registroCapitulo');

            registroNumerico.innerText = 'Capitulo actual: ' + capituloId;

        }

        function marcado ($id) {

            const id = { 
                id : $id
            };

            $.ajax ({
                type: 'POST',
                url: '/novelas/marcado',
                data: id,
                error: function (response) {
                    console.log(response)
                },
                success: function (response){
                    console.log('funciono')
                }

            });

            // $.post('/novelas/marcado', id, function (response) {
            //     console.log(response)
            // })

        }


        $(document).ready(function(){

            cargarNovelas();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            function cargarNovelas () {
                $.ajax ({
                    url: '/novelas',
                    type: 'GET',
                    success: function (response){
                        let novelas = JSON.parse(response);
                        let template = '';
                        let template2 = '';
                        novelas.forEach(task => {
                            template += 
                            `<div class="carta" onclick="cargarCapitulos(${task.id})">
                                <img src="${task.linkImage}" alt="Super Security in the City" class="Portada-Novel">
                                <h4 id="Titulo-novela" class="Titulo-Novel">${task.titulo}</h4>
                            </div>`
                            template2 += `<option value="${task.id}">${task.titulo}</option>`
                        })
                        $('#cartas').html(template);
                        $('#id_Novelas').html(template2);
                    }
                })
            };

            $('#crear-form').submit(function (e) {
                const postData = {
                    titulo: $('#titulo').val(),
                    linkImage: $('#linkImage').val(),
                    slug: $('#slug').val(),
                    descripcion: $('#discripcion').val()
                };
                $.post('/novelas/create', postData, function (response) {
                    cargarNovelas();
                    $('#crear-form').trigger('reset');
                })
                e.preventDefault();
            })

            $('#subir-form').submit(function (e) {
                const postData = {
                    numero: $('#numero').val(),
                    tituloCap: $('#tituloCap').val(),
                    id_novelas: $('#id_Novelas').val(),
                    capitulo: $('#capitulo').val()
                };
                $.post('/novelas/subir', postData, function (response) {
                    $('#subir-form').trigger('reset');
                })
                e.preventDefault();
            })

            $('#search').keyup(function (e) {

                if ($('#search').val()) {



                    const $id = $('#search').val();

                    const id = { 
                        id : $id
                    };

                    $.post('/novelas/buscar', id, function (response) {
                        let busqueda = JSON.parse(response);
                        let template= '';

                        busqueda.forEach(task => {

                            template += 
                            `<div class="carta" onclick="cargarCapitulos(${task.id})">
                                <img src="${task.linkImage}" alt="${task.titulo}" class="Portada-Novel">
                                <h4 class="Titulo-Novel">${task.titulo}</h4>
                            </div>`
                        });

                        $('#cartas').html('');
                        $('#cartas').html(template);
                    })

                } else {

                    cargarNovelas();

                }

            });

        });

</script>

@endsection