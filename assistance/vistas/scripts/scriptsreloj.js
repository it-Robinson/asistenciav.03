document.addEventListener('DOMContentLoaded', function () {
    const contactForm = document.querySelector('#contactForm');
    const codigo = document.querySelector('#codigo');
    const entrada = document.querySelector('#entrada');
    const salida = document.querySelector('#salida');

    // Reloj digital
    let actualizarHora = function () {
        let fecha = new Date(),
            horas = fecha.getHours(),
            minutos = fecha.getMinutes(),
            segundos = fecha.getSeconds(),
            diaSemana = fecha.getDay(),
            dia = fecha.getDate(),
            mes = fecha.getMonth(),
            year = fecha.getFullYear();

        let pHoras = document.getElementById('horas'),
            pMinutos = document.getElementById('minutos'),
            pSegundos = document.getElementById('segundos'),
            pDiaSemana = document.getElementById('diaSemana'),
            pDia = document.getElementById('dia'),
            pMes = document.getElementById('mes'),
            pYear = document.getElementById('year');

        let semana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        pDiaSemana.textContent = semana[diaSemana];

        pDia.textContent = dia;
        let meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        pMes.textContent = meses[mes];

        pYear.textContent = year;

        // Convertir a formato de 24 horas
        if (horas < 10) { horas = "0" + horas; }
        if (minutos < 10) { minutos = "0" + minutos; }
        if (segundos < 10) { segundos = "0" + segundos; }

        pHoras.textContent = horas;
        pMinutos.textContent = minutos;
        pSegundos.textContent = segundos;
    }

    actualizarHora();
    setInterval(actualizarHora, 1000);

    if (contactForm) {
        contactForm.onsubmit = function (e) {
            e.preventDefault();
            if (codigo.value === '') {
                message('error', 'EL CODIGO ES REQUERIDO');
            } else {
                const data = new FormData(contactForm);
                axios.post(ruta + 'controllers/asistenciaController.php?option=registrar', data)
                    .then(function (response) {
                        const info = response.data;
                        message(info.tipo, info.mensaje);
                        if (info.tipo === 'success') {
                            codigo.value = '';
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        }
    } else {
        console.error('El formulario de contacto no se encontró en el DOM.');
    }
});
