(function() {

    obtenerTareas();
    let tareas = [];
    let filtradas = [];

    // boton para mostrar Modal de agreagr tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', function() {
        mostrarFormulario(false);
    }); 

    // Filtros de busqueda
    const filtros = document.querySelectorAll('#filtros input[type="radio"]');
    filtros.forEach(radio => {
        radio.addEventListener('input', filtrarTareas);
    });

    // Funcion para poder filtrar las tareas
    function filtrarTareas(e) {
        const filtro = e.target.value;

        if(filtro !== '') {
            filtradas = tareas.filter(tarea => tarea.estado === filtro);
        } else {
            filtradas = [];
        }

        mostrarTareas();
    }

    // Obtener todas las tareas de la api
    async function obtenerTareas() {

        try {
            const id = obtenerProyecto();
            const url = `/api/tareas?id=${id}`;
            const respuesta = await fetch(url);
            
            const resultado = await respuesta.json();
            tareas = resultado.tareas;
            mostrarTareas();
            
        } catch (error) {
            console.log(error);
        }
    }

    // Mostrar todas las tareas en el dashboard
    function mostrarTareas() {
        limpiarTareas();

        totalPendientes();
        totalCompletas();


        const arrayTareas = filtradas.length ? filtradas : tareas;

        if(arrayTareas.length === 0) {
            const contenedorTareas = document.querySelector('#listado-tareas');

            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No hay tareas aún';
            textoNoTareas.classList.add('no-tareas');

            contenedorTareas.appendChild(textoNoTareas);
            return;
        }

        const estados = {
            0: 'Pendiente',
            1: 'Completa'
        }

        arrayTareas.forEach(tarea => {
            // Li para cada tarea
            const contenedorTareas = document.createElement('LI');
            // asignar un data dinamicamente
            contenedorTareas.dataset.tareaId = tarea.id;
            contenedorTareas.classList.add('tarea');

            // Titulo de cada tarea
            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;
            // doble clic para cambiar su nombre
            nombreTarea.ondblclick = function() {
                mostrarFormulario(true, {...tarea});
            };

            // SECCION PARA OPCIONES DE CADA TAREA
            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            // Botones
            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.ondblclick = function() {
                // sacar el objeto de memoria, crear una copia y la copia es la que se modifica, ya que sino se modifica desde antes el objeto de tareas
                cambiarEstadoTarea({...tarea});
            };

            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.onclick = function() {
                confirmarEliminarTarea({...tarea});
            };


            // AGREGAR LOS ELEMENTOS AL DOM
            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

            contenedorTareas.appendChild(nombreTarea);
            contenedorTareas.appendChild(opcionesDiv);

            const listadoTareas = document.querySelector('#listado-tareas');
            listadoTareas.appendChild(contenedorTareas);
        });
    }

    // Revisar si hay tareas pendientes
    function totalPendientes() {
        const totalPendientes = tareas.filter(tarea => tarea.estado === '0');
        const pendientesRadio = document.querySelector('#pendientes');

        if(totalPendientes.length === 0) {
            pendientesRadio.disabled = true;
        } else {
            pendientesRadio.disabled = false;
        }
    }

    // Revisar si hay tareas completas
    function totalCompletas() {
        const totalCompletas = tareas.filter(tarea => tarea.estado === '1');
        const completasRadio = document.querySelector('#completadas');

        if(totalCompletas.length === 0) {
            completasRadio.disabled = true;
        } else {
            completasRadio.disabled = false;
        }
    }

    // Crear un modal con animacion para agregar nuevas tareas
    function mostrarFormulario(editar = false, tarea = {}) {
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>${editar ? 'Editar Tarea' : 'Agregar una nueva tarea'}</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input
                        type="text"
                        class="tarea"
                        id="tarea"
                        name="tarea"
                        placeholder="${tarea.nombre ? 'Editar Tarea' : 'Agregar tarea al proyecto actual'}"
                        value="${tarea.nombre ? tarea.nombre : ''}"
                    />
                </div>
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="${tarea.nombre ? 'Guardar Cambios' : 'Añadir Tarea'}"/>
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>
        `;

        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 10);

        // Evitar que se de muchas veces clic a agregado
        let agregado = 0;

        // Delegation para cuando se da clic en alguna parte del modal
        modal.addEventListener('click', function(e) {
            e.preventDefault();

            if(e.target.classList.contains('cerrar-modal')) {

                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');

                setTimeout(() => {
                    modal.remove();
                }, 500);
            } else if(e.target.classList.contains('submit-nueva-tarea') && agregado === 0) {
                const nombreTarea = document.querySelector('#tarea').value.trim();
                if(nombreTarea === '') {
                    // Mostrar alerta
                    mostrarAlerta('El nombre de la tarea es obligatorio', 'error', document.querySelector('.formulario legend'));
        
                    return;
                }

                if(editar) {
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea);
                } else {
                    agregarTarea(nombreTarea);
                }
                agregado = 1;
            }

        });

        document.querySelector('.dashboard').appendChild(modal);
    }

    // Mostrar mensaje en interfaz
    function mostrarAlerta(mensaje, tipo, referencia) {

        // Prevenir creacion de mas alertas
        const alertaPrevia = document.querySelector('.alerta');
        if(alertaPrevia) {
            alertaPrevia.remove();
        }

        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;

        // insertar un elemento antes de cerrar el otro
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        // Eliminar alerta despues de cierto tiempo
        setTimeout(() => {
            alerta.remove();
        }, 5000);
    }

    // Consultar el servidor para agregar tarea al proyecto
    async function agregarTarea(tarea) {
        // Construir la peticion
        const datos = new FormData();
        datos.append('nombre', tarea);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = 'http://localhost:3000/api/tarea';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            
            const resultado = await respuesta.json();

            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario legend'));

            if(resultado.tipo === 'exito') {
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 3000);

                // Agregar el objeto de tarea al global de tareas
                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoId: resultado.proyectoId
                }

                // creando una copia del arreglo sin mutar el original
                tareas = [...tareas, tareaObj];
                mostrarTareas();
            }
            
        } catch (error) {
            console.log(error);
        }
    }

    function cambiarEstadoTarea(tarea) {
        const nuevoEstado = tarea.estado === '1' ? '0' : '1';
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);
    }

    async function actualizarTarea(tarea) {
        const { estado, id, nombre } = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        // Iterar los valores de un formData
        // for (let valor of datos.values()) {
        //     console.log(valor);
        // }

        try {
            const url = 'http://localhost:3000/api/tarea/actualizar';

            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();
            
            if(resultado.respuesta.tipo === 'exito') {
                Swal.fire(
                    resultado.respuesta.mensaje,
                    'La tarea se actualizo con éxito',
                    'success'
                );

                const modal = document.querySelector('.modal');

                if(modal) {
                    modal.remove();
                }

                // Va a iterar en el arreglo de tareas y crea un nuevo arreglo
                tareas = tareas.map(tareaMemoria => {
                    if(tareaMemoria.id === id) {
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }

                    return tareaMemoria;
                });

                mostrarTareas();
            }

        } catch (error) {
            console.log(error);
        }
        
    }

    // Alerta antes de eliminar una tarea
    function confirmarEliminarTarea(tarea) {
        Swal.fire({
            title: "Eliminar la tarea",
            text: "¿Ya no es necesaria la tarea?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, ¡eliminar!",
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {
                eliminarTarea(tarea);
              Swal.fire({
                title: "!Eliminado!",
                text: "¡La tarea se elimino correctamente",
                icon: "success"
              });
            }
          });
    }

    // eliminar la tarea con la api
    async function eliminarTarea(tarea) {
        const { estado, id, nombre } = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());
        
        try {
            const url = 'http://localhost:3000/api/tarea/eliminar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();
            
            if(resultado.respuesta.tipo === 'exito') {
                mostrarAlerta(
                    resultado.respuesta.mensaje, 
                    resultado.respuesta.tipo,
                    document.querySelector('.contenedor-nueva-tarea')
                );

                // Crear un arreglo nuevo para sacar alguno
                tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id );

                mostrarTareas();
            }

        } catch (error) {
            
        }
    }

    // Extraer los ? despues de la url actual
    function obtenerProyecto() {
        const proyectoParams = new URLSearchParams(window.location.search);
        // entries trae los datos del objeto params
        const proyecto = Object.fromEntries(proyectoParams.entries());

        return proyecto.id;
    }

    // Limpiar el contenido del dashboard para volver a generarlo
    function limpiarTareas() {
        const listadoTareas = document.querySelector('#listado-tareas');
        
        while(listadoTareas.firstChild) {
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
    }

})();