document.addEventListener("DOMContentLoaded", function() {
    const popup = document.getElementById('birthdayPopup');
    const closeBtn = document.querySelector('.close');
    const birthdayMessage = document.getElementById('birthdayMessage');
    const fireworksContainer = document.getElementById('fireworks');
    const scrollMessagePopup = document.getElementById('scrollMessagePopup'); // Mini-popup para desplazarse

    console.log("Script cargado correctamente");

    // Configurar los fuegos artificiales
    const fireworks = new Fireworks(fireworksContainer, {
        speed: 2,
        acceleration: 1.05,
        friction: 0.97,
        gravity: 1.5,
        particles: 50,
        trace: 3,
        explosion: 5,
        boundaries: {
            x: 50,
            y: 50,
            width: fireworksContainer.clientWidth,
            height: fireworksContainer.clientHeight
        },
        sound: {
            enable: true,
            files: [
                'https://cdn.jsdelivr.net/gh/crashmax-dev/fireworks.js@1.0.1/sounds/explosion0.mp3',
                'https://cdn.jsdelivr.net/gh/crashmax-dev/fireworks.js@1.0.1/sounds/explosion1.mp3',
                'https://cdn.jsdelivr.net/gh/crashmax-dev/fireworks.js@1.0.1/sounds/explosion2.mp3'
            ],
            volume: {
                min: 1,
                max: 2
            }
        }
    });

    // Obtener los datos de los usuarios desde getUsers.php
    fetch('getUsers.php')
        .then(response => response.json())
        .then(data => {
            console.log("Datos obtenidos:", data);
            const today = moment().tz(moment.tz.guess());
            const todayMonth = today.month() + 1;
            const todayDate = today.date();

            let birthdayUsers = []; // Lista para almacenar los usuarios que cumplen años

            data.forEach(usuario => {
                const birthdate = moment(usuario.fecha_nacimiento, 'YYYY-MM-DD').tz(moment.tz.guess());
                const birthMonth = birthdate.month() + 1;
                const birthDate = birthdate.date();

                if (birthMonth === todayMonth && birthDate === todayDate) {
                    console.log(`Hoy es el cumpleaños de ${usuario.nombre}`);
                    birthdayUsers.push(usuario); // Añadir el usuario a la lista de cumpleañeros
                }
            });

            if (birthdayUsers.length > 0) {
                // Crear la estructura del popup
                let messageHTML = `<h2>🎉 ¡Feliz Cumpleaños! 🎉</h2>`;  // Solo se añade una vez

                messageHTML += `<div class="birthday-list">`;  // Lista de cumpleañeros

                birthdayUsers.forEach(usuario => {
                    let imgPath = `assistance/files/usuarios/${usuario.imagen}`; // Ruta de la imagen corregida
                    console.log("Ruta de la imagen:", imgPath);

                    messageHTML += `
                        <div class="birthday-person">
                            <img src="${imgPath}" alt="${usuario.nombre}" class="birthday-image">
                            <div class="birthday-info">
                                <!-- Nombre completo centrado -->
                                <div style="text-align:center; margin-bottom: 10px;">
                                    <strong style="font-size: 18px;">${usuario.nombre} ${usuario.apellidos}</strong>
                                </div>
                                
                                <!-- Área, Puesto y Empresa alineados a la izquierda -->
                                <div style="text-align:left; font-size: 14px;">
                                    <strong>Área:</strong> ${usuario.departamento_nombre || 'SIN REGISTRO'} <br>
                                    <strong>Puesto:</strong> ${usuario.tipousuario_nombre} <br>
                                    <strong>Empresa:</strong> ${usuario.empresa || 'SIN REGISTRO'}
                                </div>
                            </div>
                        </div>
                    `;
                });

                // Cerrar el bloque de personas
                messageHTML += `</div>`;

                // **Agregar saludo personalizado dependiendo de cuántos cumpleañeros hay**
                if (birthdayUsers.length === 1) {
                    // Si es solo un cumpleañero
                    messageHTML += `
                        <p>🎂 Que este nuevo año te traiga alegrías, éxito y momentos inolvidables. ¡Gracias por ser parte de nuestro equipo y por tu dedicación! ¡Disfruta al máximo tu día especial! 🎁🥳</p>
                    `;
                } else {
                    // Si hay más de uno
                    messageHTML += `
                        <p>🎂 Hoy celebramos la vida y el camino recorrido. Que cada día esté lleno de nuevas oportunidades y felicidad. ¡Gracias por ser la chispa que hace grande a nuestra empresa! 🎁</p>
                    `;
                }

                // Insertar el contenido generado en el popup
                birthdayMessage.innerHTML = messageHTML;

                // Mostrar el popup y siempre mostrar el botón "Cerrar"
                popup.style.display = 'block';
                closeBtn.style.display = 'block'; // Mostrar siempre el botón de cerrar
                fireworks.start(); // Iniciar los fuegos artificiales

                // **Calcular la posición inicial del mini-popup y el botón de cerrar**
                updateScrollMessagePosition();
                updateCloseButtonPosition();

                // Verificar si el contenido es más grande que el popup
                if (popup.scrollHeight > popup.clientHeight) {
                    scrollMessagePopup.style.display = 'block'; // Mostrar el mini-popup que indica desplazarse

                    // Añadir listener de scroll para mover el mini-popup y el botón "Cerrar" dinámicamente
                    popup.addEventListener('scroll', function() {
                        // Posicionar dinámicamente el mini-popup y el botón "Cerrar"
                        updateScrollMessagePosition();
                        updateCloseButtonPosition();

                        // Ocultar cuando llegues al final del popup
                        if (popup.scrollTop + popup.clientHeight >= popup.scrollHeight - 50) {
                            scrollMessagePopup.style.display = 'none'; // Ocultar si estamos al final
                        } else {
                            scrollMessagePopup.style.display = 'block'; // Mostrar mientras no llegues al final
                        }
                    });
                } else {
                    scrollMessagePopup.style.display = 'none'; // No mostrar el mini-popup si no hay scroll
                }
            } else {
                console.log("Hoy no es el cumpleaños de ningún usuario.");
            }
        })
        .catch(error => console.error('Error:', error));

    // **Función para actualizar la posición del mini-popup**
    function updateScrollMessagePosition() {
        let visibleHeight = popup.clientHeight; // Altura visible del contenedor
        let scrollTop = popup.scrollTop; // Cuánto se ha desplazado
        scrollMessagePopup.style.top = (scrollTop + visibleHeight - 80) + 'px'; // Posicionar a 20px por encima del borde inferior visible
    }

    // **Función para actualizar la posición del botón "Cerrar"**
    function updateCloseButtonPosition() {
        let scrollTop = popup.scrollTop; // Cuánto se ha desplazado
        closeBtn.style.top = (scrollTop + 20) + 'px'; // Posicionar a 20px desde la parte superior visible
    }

    // Cerrar el popup con Esc, clic en "Cerrar" o fuera del contenido
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closePopup(); // Llamamos a la función para cerrar el popup
        }
    });

    closeBtn.onclick = function() {
        closePopup(); // Llamamos a la función para cerrar el popup
    }

    // **Cerrar al hacer clic fuera del popup**
    window.addEventListener('click', function(event) {
        if (event.target == popup) return; // Asegúrate de que el clic no sea dentro del popup
        if (!popup.contains(event.target) && popup.style.display === 'block') {
            closePopup(); // Cerrar si el clic es fuera del popup
        }
    });

    // Función para cerrar el popup
    function closePopup() {
        fireworks.stop(); // Detener los fuegos artificiales primero
        popup.style.display = 'none'; // Luego ocultar el popup
    }
});
