var nav_1_elements = [
    {
        text: 'Pedidos',
        active: false,
        icon: 'fa fa-shopping-cart',
        cf: 'pedidos/explorar',
        anchor: true,
        subelements: [],
        sections: ['pedidos/explorar']
    },
    {
        text: 'Productos',
        active: false,
        icon: 'fa fa-book',
        cf: 'productos/explorar',
        anchor: true,
        subelements: [],
        sections: ['productos/explorar']
    },
    {
        text: 'Fletes',
        active: false,
        icon: 'fa fa-truck',
        cf: 'fletes/explorar',
        anchor: true,
        subelements: [],
        sections: ['fletes/explorar']
    },
    {
        text: 'Usuarios',
        active: false,
        icon: 'fa fa-user',
        cf: 'usuarios/explorar',
        anchor: true,
        subelements: [],
        sections: ['usuarios/explore', 'usuarios/profile', 'usuarios/import', 'usuarios/add', 'usuarios/notes']
    },
    {
        text: 'Estadísticas',
        active: false,
        icon: 'fa fa-chart-line',
        cf: '',
        subelements: [
            {
                text: 'Ventas',
                active: false,
                icon: 'fa fa-shopping-cart',
                cf: 'pedidos/resumen_mes',
                anchor: false,
                sections: ['pedidos/resumen_mes']
            }
        ],
        sections: []
    },
    {
        text: 'Datos',
        active: false,
        icon: 'fa fa-table',
        cf: '',
        subelements: [
            {
                text: 'Posts',
                active: false,
                icon: 'fa fa-newspaper',
                cf: 'posts/explore',
                anchor: false,
                sections: ['posts/explore', 'posts/info', 'posts/add', 'posts/edit', 'posts/import', 'posts/list_elements']
            },
            {
                text: 'Archivos',
                active: false,
                icon: 'fa fa-file',
                cf: 'archivos/imagenes',
                anchor: true,
                sections: ['archivos/imagenes', 'archivos/cargar', 'archivos/carpetas']
            },
            {
                text: 'Eventos',
                active: false,
                icon: 'fa fa-calendar',
                cf: 'eventos/explorar',
                anchor: true,
                sections: []
            }
        ],
        sections: []
    },
    {
        text: 'Ajustes',
        active: false,
        icon: 'fa fa-cog',
        cf: '',
        subelements: [
            {
                text: 'Parámetros',
                active: false,
                icon: 'fa fa-sliders-h',
                cf: 'admin/sis_opcion',
                sections: ['admin/sis_opcion']
            },
            {
                text: 'Ciudades y lugares',
                active: false,
                icon: 'fa fa-map-marker',
                cf: 'lugares/sublugares/51/',
                anchor: true,
                sections: ['lugares/sublugares']
            },
            {
                text: 'Items',
                active: false,
                icon: 'fa fa-bars',
                cf: 'items/listado',
                anchor: true,
                sections: ['items/listado', 'items/import', 'items/import_e']
            },
            {
                text: 'Base de datos',
                active: false,
                icon: 'fa fa-database',
                cf: 'tablas/item',
                anchor: true,
                sections: []
            },
            {
                text: 'Procesos',
                active: false,
                icon: 'fa fa-list',
                cf: 'admin/procesos',
                anchor: true,
                sections: []
            }
        ],
        sections: []
    }
];