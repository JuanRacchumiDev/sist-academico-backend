<?php
$persona = $content['persona'];
$empresa = $content['empresa'];
$programas = $content['programas'];
$pago = $content['pago'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 1em;
        }

        .font-size-2em: {
            font-size: 2em;
        }

        .font-size-1em {
            font-size: 1em;
        }

        .font-strong {
            font-weight: bold;
        }

        .font-italic {
            font-style: italic;
        }

        .text-center {
            text-align: center;
        }

        .text-justify: {
            text-align: justify;
        }

        .text-underline {
            text-decoration: underline;
        }

        .border-dotted {
            border: 1px dotted #000; 
        }

        .border-dashed {
            border: 1px dashed #000;
        }

        .border-solid {
            border: 1px solid #000;
        }

        .w-20 {
            margin: 0 auto;
            width: 20%;
        }

        .w-30 {
            margin: 0 auto;
            width: 30%;
        }

        .w-32 {
            margin: 0 auto;
            width: 32%;
        }

        .w-40 {
            margin: 0 auto;
            width: 40%;
        }

        .w-49 {
            margin: 0 auto;
            width: 49%;
        }

        .w-50 {
            margin: 0 auto;
            width: 50%;
        }

        .w-60 {
            margin: 0 auto;
            width: 60%;
        }

        .w-70 {
            margin: 0 auto;
            width: 70%;
        }

        .w-80 {
            margin: 0 auto;
            width: 80%;
        }

        .w-90 {
            margin: 0 auto;
            width: 90%;
        }

        .w-100 {
            margin: 0 auto;
            width: 100%;
        }

        .mt-5 {
            margin-top: 5px;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .mb-5 {
            margin-bottom: 5px;
        }

        .mb-10 {
            margin-bottom: 10px;
        }
        
        .p-5 {
            padding: 5px;
        }

        .pt-5 {
            padding-top: 5px;
        }

        .pb-5 {
            padding-bottom: 5px;
        }

        .p-10 {
            padding: 10px;
        }

        .d-inline-block {
            display: inline-block;
        }

        .d-block {
            display: block;
        }

        .d-inline {
            display: inline;
        }
    </style>
</head>
<body>
    <h1 class="text-center w-80 mb-5">{{ $title }}</h1>
    <p class="mb-10">Yo, <strong class="text-underline">{{ $persona['nombre_completo'] }}</strong> identificado (a) con D.N.I. N° {{ $persona['numero_documento'] }} autorizo a través de la grabación de voz la participación en los programas de capacitación universitaria desarrollado con la {{ $empresa['razon_social'] }}.</p>
    <div class="mb-10">
    <?php $contador = 0; ?>
    <?php foreach($programas as $programa): ?>
        <?php $contador++; ?>
        <p class="w-100 mb-5">
            <span class="w-20 d-inline-block pt-5 pb-5">PROGRAMA {{ $contador }}</span>
            <span class="w-70 border-solid p-5 d-inline-block">{{ $programa['nombre'] }}</span>
        </p>
    <?php endforeach; ?>
    </div>
    <div class="w-100 d-block mb-10">
        <div class="w-49 d-inline-block">
            <div class="w-100 d-block">
                <span class="w-40 d-inline-block">EMAIL</span>
                <span class="w-50 d-inline-block">{{ $persona['email'] }}</span>
            </div>
        </div>
        <div class="w-49 d-inline-block">
            <span class="w-30 d-inline-block">TELÉFONO</span>
            <span class="w-60 d-inline-block">{{ $persona['telefono'] }}</span>
        </div>
    </div>
    <div class="w-100 d-block">
        <div class="w-49 d-inline-block">
            <span class="w-40 d-inline-block">DIRECCIÓN</span>
            <span class="w-50 d-inline-block">{{ $persona['direccion'] }}</span>
        </div>
        <div class="w-49 d-inline-block">
            <span class="w-30 d-inline-block">ASESOR</span>
            <span class="w-60 d-inline-block">JUAN PÉREZ</span>
        </div>
    </div>
    <div class="border-dashed">
        <div class="p-5">
            <p>DATOS DE LA ENTIDAD CON QUIEN ASUME EL COMPROMISO</p>
            <div class="w-100">
                <span class="w-20 d-inline-block pt-5 pb-5">RAZÓN SOCIAL</span>
                <span class="w-70 border-solid p-5 d-inline-block">{{ $empresa['razon_social'] }}</span>
            </div>
            <div class="w-100">
                <span class="w-20 d-inline-block pt-5 pb-5">RUC</span>
                <span class="w-70 border-solid p-5 d-inline-block">{{ $empresa['ruc'] }}</span>
            </div>
            <p>DETALLES DE COMPROMISO DE PAGO</p>
            <div class="w-100">
                <div class="w-32 d-inline-block">
                    INICIO: MES <strong class="text-underline">DICIEMBRE</strong>
                </div>
                <div class="w-32 d-inline-block">
                    AÑO <strong class="text-underline">2025</strong>
                </div>
                <div class="w-32 d-inline-block">
                    MATRÍCULA <strong class="border-solid">120.00</strong>
                </div>
            </div>
            <div class="w-100">
                <div class="w-32 d-inline-block">
                    CUOTAS <strong class="border-solid">12</strong>
                </div>
                <div class="w-32 d-inline-block">
                    MONTO POR CUOTA <strong class="border-solid">120.00</strong>
                </div>
                <div class="w-32 d-inline-block">
                    TOTAL <strong class="border-solid">1560.00</strong>
                </div>
            </div>
        </div>
    </div>
    <div class="w-100">
        <p class="text-justify mb-5"><strong>- MÉTODO DE ENSEÑANZA - APRENDIZAJE :</strong> Los programas se desarrollan integramente , incluida las evaluaciones
periódicas, en regimen de enseñaza a distancia, sin requerir el desplazamiento fisico del participante. Se emplea
diversos métodos didacticos : Estudio del material auto instructivo</p>
        <p class="text-justify mb-5"><strong>- MATERIAL DE ESTUDIO :</strong> El material de estudio consta de módulos virtuales que incluyen separatas con lecciones
claras sencillas y practicas.</p>
        <p class="text-justify"><strong>- ¿Cómo son las evaluaciones? :</strong> Cada módulo virtual incluye exámenes que debe ser desarrollados para probar
un cabal aprendizaje, el participante emite las respuestas a la sede de ESUCAP, para su calificación desarrollan las
preguntas por escrito o virtual y lo suben a nuestra plataforma virtual o lo envian por Email a
evaluaciones@esucap.com</p>
        <p class="text-justify"><strong>- PREGUNTAS: </strong> Si usted tiene alguna duda, pregunta o comentario sobre las capacitaciones, o está interesado en
adquirir otros cursos y paquetes especiales, por favor, no dude en contactar a un asesor de la Compañía a través de
llamada telefónica o a través de los medios de contacto publicados en la página web www.esucap.edu.pe</p>
    </div>
</body>
</html>