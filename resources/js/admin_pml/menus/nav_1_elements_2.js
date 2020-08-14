var nav_1_elements = [
    {
        text: 'Pedidos',
        active: false,
        icon: 'fa fa-shopping-cart',
        cf: 'pedidos/explorar',
        anchor: true,
        subelements: [],
        sections: ['pedidos/explorar', 'pedidos/info', 'pedidos/payu', 'pedidos/extras', 'pedidos/edit', 'pedidos/test']
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
        text: 'Usuarios',
        active: false,
        icon: 'fa fa-user',
        cf: 'usuarios/explorar',
        anchor: true,
        subelements: [],
        sections: ['usuarios/explore', 'usuarios/profile', 'usuarios/import', 'usuarios/add', 'usuarios/notes']
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
    }
];