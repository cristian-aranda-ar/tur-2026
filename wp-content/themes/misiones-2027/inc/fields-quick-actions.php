<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'acf/init', function () {
    acf_add_local_field_group( [
        'key'      => 'group_scfb_quick_actions',
        'title'    => 'Acciones Rápidas',
        'location' => [ [ [
            'param'    => 'page_type',
            'operator' => '==',
            'value'    => 'front_page',
        ] ] ],
        'menu_order' => 10,
        'position'   => 'normal',
        'style'      => 'seamless',
        'fields' => [
            [
                'key'          => 'field_scfb_acciones_cards',
                'label'        => 'Cards',
                'name'         => 'acciones_cards',
                'type'         => 'repeater',
                'min'          => 1,
                'max'          => 8,
                'layout'       => 'table',
                'button_label' => 'Agregar acción',
                'sub_fields'   => [
                    [
                        'key'           => 'field_scfb_qa_label',
                        'label'         => 'Título',
                        'name'          => 'qa_label',
                        'type'          => 'text',
                        'default_value' => 'Acción',
                        'column_width'  => '20',
                    ],
                    [
                        'key'           => 'field_scfb_qa_desc',
                        'label'         => 'Descripción',
                        'name'          => 'qa_desc',
                        'type'          => 'textarea',
                        'rows'          => 2,
                        'new_lines'     => '',
                        'default_value' => '',
                        'column_width'  => '35',
                    ],
                    [
                        'key'           => 'field_scfb_qa_href',
                        'label'         => 'Vínculo',
                        'name'          => 'qa_href',
                        'type'          => 'text',
                        'default_value' => '#',
                        'column_width'  => '25',
                    ],
                    [
                        'key'           => 'field_scfb_qa_icon',
                        'label'         => 'Ícono',
                        'name'          => 'qa_icon',
                        'type'          => 'select',
                        'default_value' => 'ticket',
                        'allow_null'    => 0,
                        'return_format' => 'value',
                        'column_width'  => '20',
                        'choices'       => [
                            'ticket'   => 'Entradas / Ticket',
                            'hotel'    => 'Alojamiento / Hotel',
                            'plane'    => 'Cómo llegar / Avión',
                            'phone'    => 'Informes / Teléfono',
                            'map-pin'  => 'Ubicación / Pin',
                            'calendar' => 'Eventos / Calendario',
                            'compass'  => 'Explorar / Brújula',
                            'star'     => 'Destacado / Estrella',
                            'info'     => 'Información',
                        ],
                    ],
                ],
            ],
        ],
    ] );
} );
