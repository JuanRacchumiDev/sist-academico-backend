<?php

return [
    'horas_academicas_default' => 120,
    'tipo_programas' => [
        'tipo_certificacion' => 'certificacion',
        'tipo_diplomado' => 'diplomado',
        'tipo_especializacion' => 'especializacion',
        'tipo_capacitacion' => 'capacitacion'
    ],
    'clases' => [
        'tipo-documento' => 1000,
        'perfil' => 1001,
        'tipo-programa' => 1002,
        'tipo-certificado' => 1003,
        'sucursal' => 1004,
        'universidad' => 1005,
        'segmento' => 1006,
        'grupo' => 1007,
        'banco-cuenta' => 1008,
        'categoria-programa' => 1009,
        'estado-matriicula' => 1010,
        'estado-pago' => 1011,
        'forma-pago' => 1012,
        'sede' => 1013
    ],
    'styles_pdfs' => [
        'certificacion' => [
            'cert_col_abogados' => [
                'alumno' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#0092FF',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '30'
                ],
                'fechas' => [
                    'color' => '#D5A701',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'cert_col_administracion' => [
                'alumno' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#0092FF',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '30'
                ],
                'fechas' => [
                    'color' => '#007A3E',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'cert_col_arquitectos' => [
                'alumno' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#0092FF',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '30'
                ],
                'fechas' => [
                    'color' => '#C6A54F',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'cert_col_contadores' => [
                'alumno' => [
                    'color' => '#0D377F',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#0D377F',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '30'
                ],
                'fechas' => [
                    'color' => '#B89439',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'cert_col_enfermeros' => [
                'alumno' => [
                    'color' => '#191C43',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#191C43',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '30'
                ],
                'fechas' => [
                    'color' => '#02BFBE',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'cert_col_ingenieros' => [
                'alumno' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#6C0E10',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '30'
                ],
                'fechas' => [
                    'color' => '#C6A54F',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'cert_lamas' => [
                'alumno' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#1E3D8A',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '30'
                ],
                'fechas' => [
                    'color' => '#A87D26',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'cert_col_psicologia' => [
                'alumno' => [
                    'color' => '#0C2468',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#1D2C5B',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '30'
                ],
                'fechas' => [
                    'color' => '#A87D26',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ]
        ],
        'diplomado' => [
            'diplomado_col_abogados' => [
                'alumno' => [
                    'color' => '#D5A701',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#0092FF',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '30'
                ],
                'fechas' => [
                    'color' => '#D5A701',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'diplomado_col_administracion' => [
                'alumno' => [
                    'color' => '#007A3E',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '30'
                ],
                'fechas' => [
                    'color' => '#007A3E',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#1D1D1B',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'diplomado_col_arquitectos' => [
                'alumno' => [
                    'color' => '#D5A701',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '30'
                ],
                'fechas' => [
                    'color' => '#D5A701',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#1D1D1B',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'diplomado_col_contadores' => [
                'alumno' => [
                    'color' => '#B89439',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#0D377F',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '30'
                ],
                'fechas' => [
                    'color' => '#B89439',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#1D1D1B',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'diplomado_col_enfermeros' => [
                'alumno' => [
                    'color' => '#02BFBE',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#191C43',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '30'
                ],
                'fechas' => [
                    'color' => '#E09227',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#1D1D1B',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'diplomado_lamas' => [
                'alumno' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#1E3D8A',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '30'
                ],
                'fechas' => [
                    'color' => '#E5231E',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#1D1D1B',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'diplomado_col_ingenieros' => [
                'alumno' => [
                    'color' => '#BC9550',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#6C0E10',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '30'
                ],
                'fechas' => [
                    'color' => '#BC9550',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#1D1D1B',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'diplomado_col_psicologos' => [
                'alumno' => [
                    'color' => '#A87D26',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#0C2468',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '30'
                ],
                'fechas' => [
                    'color' => '#A87D26',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#1D1D1B',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
        ],
        'especializacion' => [
            'anchoMaximoAlumno' => 673.60,
            'anchoMaximoPrograma' => 673.60,
            'anchoMaximoFechas' => 673.60,
            'anchoMaximoDirector' => 673.60,
            'especializacion_col_abogados' => [
                'alumno' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#0092FF',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '47',
                    'line_height' => 1.0,
                    'factor_conversion' => 0.55
                ],
                'fechas' => [
                    'color' => '#D5A701',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'especializacion_col_administracion' => [
                'alumno' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '47',
                    'line_height' => 0.8
                ],
                'fechas' => [
                    'color' => '#007A3E',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'especializacion_col_arquitectos' => [
                'alumno' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '47',
                    'line_height' => 0.8
                ],
                'fechas' => [
                    'color' => '#C6A54F',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'especializacion_col_contadores' => [
                'alumno' => [
                    'color' => '#0D377F',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#0D377F',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '47',
                    'line_height' => 0.8
                ],
                'fechas' => [
                    'color' => '#B89439',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'especializacion_col_enfermeros' => [
                'alumno' => [
                    'color' => '#191C43',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#191C43',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '47',
                    'line_height' => 0.8
                ],
                'fechas' => [
                    'color' => '#02BFBE',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'especializacion_col_ingenieros' => [
                'alumno' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#6C0E10',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '47',
                    'line_height' => 0.8
                ],
                'fechas' => [
                    'color' => '#A87D26',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'especializacion_col_psicologos' => [
                'alumno' => [
                    'color' => '#0C2468',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#0C2468',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '47',
                    'line_height' => 0.8
                ],
                'fechas' => [
                    'color' => '#A87D26',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ],
            'especializacion_lamas' => [
                'alumno' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '78'
                ],
                'programa' => [
                    'color' => '#1E3D8A',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '47',
                    'line_height' => 0.8
                ],
                'fechas' => [
                    'color' => '#E5231E',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ],
                'director' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'Archivo-Medium.ttf',
                    'fontSize' => '12'
                ]
            ]
        ],
        'capacitacion' => [
            'anchoMaximoAlumno' => 673.60,
            'anchoMaximoPrograma' => 673.60,
            'anchoMaximoFechas' => 673.60,
            'anchoMaximoDirector' => 673.60,
            'default_uno' => [
                'alumno' => [
                    'color' => '#000000',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '79'
                ],
                'programa' => [
                    'color' => '#000000',
                    'custom_font' => false,
                    'font' => 'Calibri',
                    'fontSize' => '33'
                ],
                'fechas' => [
                    'color' => '#589AFC',
                    'custom_font' => false,
                    'font' => 'Calibri',
                    'fontSize' => '19'
                ]
            ],
            'capacitacion_col_enfermeros_huanuco' => [
                'alumno' => [
                    'color' => '#191C43',
                    'custom_font' => true,
                    'font' => 'GreatVibes-Regular.ttf',
                    'fontSize' => '79'
                ],
                'programa' => [
                    'color' => '#191C43',
                    'custom_font' => true,
                    'font' => 'Anton.ttf',
                    'fontSize' => '31'
                ],
                'fechas' => [
                    'color' => '#02BFBE',
                    'custom_font' => true,
                    'font' => 'Archivo-Regular.ttf',
                    'fontSize' => '17'
                ]
            ]
        ]
    ]
];
