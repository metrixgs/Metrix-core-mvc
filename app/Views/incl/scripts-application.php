</div>
<!-- end main content-->
</div>
<!-- END layout-wrapper -->



<!--start back-to-top-->
<button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>
<!--end back-to-top-->

<!--preloader-->
<div id="preloader">
    <div id="status">
        <div class="spinner-border text-primary avatar-sm" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>
</div>

<!-- vendor js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<!-- JAVASCRIPT -->
<script src="<?= base_url() . "public/files/"; ?>libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() . "public/files/"; ?>libs/simplebar/simplebar.min.js"></script>
<script src="<?= base_url() . "public/files/"; ?>libs/node-waves/waves.min.js"></script>
<script src="<?= base_url() . "public/files/"; ?>libs/feather-icons/feather.min.js"></script>
<script src="<?= base_url() . "public/files/"; ?>js/pages/plugins/lord-icon-2.1.0.js"></script>
<script src="<?= base_url() . "public/files/"; ?>js/plugins.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script type="text/javascript" src="<?= base_url() . "public/files/"; ?>libs/choices.js/public/assets/scripts/choices.min.js"></script>
<script type="text/javascript" src="<?= base_url() . "public/files/"; ?>libs/flatpickr/flatpickr.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<!-- apexcharts -->
<script src="<?= base_url() . "public/files/"; ?>libs/apexcharts/apexcharts.min.js"></script>

<!-- Vector map-->
<script src="<?= base_url() . "public/files/"; ?>libs/jsvectormap/js/jsvectormap.min.js"></script>
<script src="<?= base_url() . "public/files/"; ?>libs/jsvectormap/maps/world-merc.js"></script>

<!-- gridjs js -->
<script src="<?= base_url() . "public/files/"; ?>libs/gridjs/gridjs.umd.js"></script>

<!-- Dashboard init -->
<script src="<?= base_url() . "public/files/"; ?>js/pages/dashboard-job.init.js"></script>

<!--datatable js-->
<script src="<?= base_url() . "public/files/"; ?>js/jquery.dataTables.min.js"></script>

<!-- DataTables Select Extension (opcional) -->
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.4/css/select.dataTables.min.css">
<script src="https://cdn.datatables.net/select/1.3.4/js/dataTables.select.min.js"></script>

<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<!-- multi.js -->
<script src="<?= base_url() . "public/files/"; ?>libs/multi.js/multi.min.js"></script>
<!-- autocomplete js -->
<script src="<?= base_url() . "public/files/"; ?>libs/%40tarekraafat/autocomplete.js/autoComplete.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const collapsibleMenus = document.querySelectorAll('.nav-link[data-bs-toggle="collapse"]');
        const simpleLinks = document.querySelectorAll('.nav-link.menu-link');  // Enlaces simples

        let openedMenu = localStorage.getItem('openedMenu') || null; // Obtener el ID del menú abierto de localStorage
        let activeLink = localStorage.getItem('activeLink') || null;  // Recuperar el enlace activo

        // Función para resaltar el enlace activo
        function setActiveLink(link) {
            // Eliminar 'active' de todos los enlaces
            document.querySelectorAll('.nav-link').forEach(item => item.classList.remove('active'));
            document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));

            // Agregar 'active' al enlace clicado
            link.classList.add('active');
            link.closest('.nav-item').classList.add('active');

            // Si el enlace tiene un submenú, también resaltamos el submenú
            if (link.closest('.nav-item').querySelector('.collapse')) {
                link.closest('.nav-item').querySelector('.collapse').classList.add('show');
            }

            // Guardar el estado del enlace activo en localStorage
            localStorage.setItem('activeLink', link.id);
        }

        // Lógica para los menús con submenús colapsables
        collapsibleMenus.forEach(menu => {
            const targetId = menu.getAttribute('href').replace('#', '');
            const targetMenu = document.getElementById(targetId);

            if (openedMenu === targetId || localStorage.getItem(targetId) === 'true') {
                targetMenu.classList.add('show');
                menu.setAttribute('aria-expanded', 'true');
                menu.classList.add('active');
                menu.closest('.nav-item').classList.add('active');
            } else {
                targetMenu.classList.remove('show');
                menu.setAttribute('aria-expanded', 'false');
                menu.classList.remove('active');
                menu.closest('.nav-item').classList.remove('active');
            }

            menu.addEventListener('click', function () {
                const isExpanded = targetMenu.classList.contains('show');

                if (isExpanded) {
                    targetMenu.classList.remove('show');
                    menu.setAttribute('aria-expanded', 'false');
                    menu.classList.remove('active');
                    menu.closest('.nav-item').classList.remove('active');
                    localStorage.setItem(targetId, 'false');
                } else {
                    targetMenu.classList.add('show');
                    menu.setAttribute('aria-expanded', 'true');
                    menu.classList.add('active');
                    menu.closest('.nav-item').classList.add('active');
                    localStorage.setItem(targetId, 'true');

                    collapsibleMenus.forEach(otherMenu => {
                        if (otherMenu !== menu) {
                            const otherTargetId = otherMenu.getAttribute('href').replace('#', '');
                            const otherTargetMenu = document.getElementById(otherTargetId);
                            otherTargetMenu.classList.remove('show');
                            otherMenu.setAttribute('aria-expanded', 'false');
                            otherMenu.classList.remove('active');
                            otherMenu.closest('.nav-item').classList.remove('active');
                            localStorage.setItem(otherTargetId, 'false');
                        }
                    });
                }

                localStorage.setItem('openedMenu', targetId);
                setActiveLink(menu);  // Resaltar el enlace activo cuando se hace clic
            });
        });

        // Lógica para los enlaces simples
        simpleLinks.forEach(link => {
            link.addEventListener('click', function () {
                // Si se hace clic en un enlace simple, cerramos todos los menús con submenú abierto
                collapsibleMenus.forEach(menu => {
                    const targetId = menu.getAttribute('href').replace('#', '');
                    const targetMenu = document.getElementById(targetId);
                    targetMenu.classList.remove('show');
                    menu.setAttribute('aria-expanded', 'false');
                    menu.classList.remove('active');
                    menu.closest('.nav-item').classList.remove('active');
                    localStorage.setItem(targetId, 'false');
                });

                // Resaltar el enlace simple como activo
                setActiveLink(link);
            });

            // Verificar si el enlace está activo después de recargar la página
            if (link.id === activeLink) {
                setActiveLink(link);  // Resaltar el enlace como activo si coincide con el estado almacenado
            }
        });
    });

</script>

<!-- init js -->
<script src="<?= base_url() . "public/files/"; ?>js/pages/form-advanced.init.js"></script>
<!-- input spin init -->
<script src="<?= base_url() . "public/files/"; ?>js/pages/form-input-spin.init.js"></script>
<!-- input flag init -->
<script src="<?= base_url() . "public/files/"; ?>js/pages/flag-input.init.js"></script>

<!-- Inicializar las DataTables -->
<script>
    $(document).ready(function () {
        // Función para inicializar DataTables y agregar filtros
        function initializeDataTables() {
            $('.datatable').each(function () {
                var table = $(this).DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": false, // Ordenamiento deshabilitado
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/es_es.json" // Traducción al español
                    },
                    // Configuración de botones
                    "dom": 'Bfrtip', // Agrega los botones al DOM
                    "buttons": [
                        {
                            extend: 'collection', // Agrupa los botones de exportación
                            text: 'Exportar', // Texto del botón principal
                            className: 'btn btn-primary', // Estilo del botón principal
                            buttons: [
                                {
                                    extend: 'copy',
                                    text: 'Copiar',
                                    className: 'dropdown-item'
                                },
                                {
                                    extend: 'csv',
                                    text: 'CSV',
                                    className: 'dropdown-item'
                                },
                                {
                                    extend: 'excel',
                                    text: 'Excel',
                                    className: 'dropdown-item'
                                },
                                {
                                    extend: 'pdf',
                                    text: 'PDF',
                                    className: 'dropdown-item'
                                },
                                {
                                    extend: 'print',
                                    text: 'Imprimir',
                                    className: 'dropdown-item'
                                }
                            ]
                        },
                        {
                            extend: 'colvis', // Botón para gestionar columnas
                            text: 'Columnas', // Texto del botón
                            className: 'btn btn-warning' // Estilo del botón
                        }
                    ],
                    "columnDefs": [
                        {
                            "targets": -1, // Última columna ("Opciones")
                            "orderable": false, // Deshabilitar ordenamiento
                            "visible": true // Asegurarse de que esté visible inicialmente
                        }
                    ],
                    // Mostrar 100 registros por página
                    "pageLength": 100, // Número de registros por página
                    "lengthMenu": [10, 25, 50, 100, 200], // Opciones del menú desplegable
                });

                // Crear filtros para cada columna
                $(this).find('thead tr').clone(true).appendTo($(this).find('thead'));
                $(this).find('thead tr:eq(1) th').each(function (i) {
                    var title = $(this).text();
                    if (title !== "Opciones" && title !== "Acciones") { // Excluir columnas específicas
                        // Agregar un input de texto para filtrar
                        $(this).html('<input type="text" placeholder="Filtrar ' + title + '" />');
                        // Evento para aplicar el filtro
                        $('input', this).on('keyup change', function () {
                            if (table.column(i).search() !== this.value) {
                                table.column(i).search(this.value).draw();
                            }
                        });
                    } else {
                        $(this).html(''); // Dejar vacía la columna de opciones o acciones
                    }
                });

                // Sincronizar la visibilidad de los filtros con las columnas
                table.on('column-visibility.dt', function (e, settings, column, state) {
                    // Actualizar la visibilidad del filtro en la segunda fila del encabezado
                    $(table.table().header()).find('tr:eq(1) th').eq(column).toggle(state);
                });
            });
        }

        // Inicializar todas las tablas con la clase "datatable"
        initializeDataTables();
    });
</script>   

<script src="<?= base_url() . "public/files/"; ?>js/pages/select2.init.js"></script>

<!-- App js -->
<script src="<?= base_url() . "public/files/"; ?>js/app.js"></script>

</body>

</html>