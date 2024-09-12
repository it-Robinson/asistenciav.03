document.addEventListener("DOMContentLoaded", function() {
    const popup = document.getElementById('birthdayPopup');
    const closeBtn = document.querySelector('.close');
    const birthdayMessage = document.getElementById('birthdayMessage');
    const fireworksContainer = document.getElementById('fireworks');

    console.log("Script cargado correctamente"); // Mensaje de consola para verificar que el script se ha cargado

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
            console.log("Datos obtenidos:", data); // Añadir mensaje de consola
            const today = moment().tz(moment.tz.guess()); // Obtener la fecha actual en la zona horaria local
            const todayMonth = today.month() + 1; // month() es 0-indexado, así que sumamos 1
            const todayDate = today.date();

            let birthdayNames = []; // Lista para almacenar los nombres de los usuarios que cumplen años

            data.forEach(usuario => {
                const birthdate = moment(usuario.fecha_nacimiento, 'YYYY-MM-DD').tz(moment.tz.guess()); // Convertir fecha de nacimiento a la zona horaria local
                const birthMonth = birthdate.month() + 1; // month() es 0-indexado, así que sumamos 1
                const birthDate = birthdate.date();

                console.log(`Hoy: ${todayMonth}-${todayDate}, Cumpleaños de ${usuario.nombre}: ${birthMonth}-${birthDate}`);

                if (birthMonth === todayMonth && birthDate === todayDate) {
                    console.log(`Hoy es el cumpleaños de ${usuario.nombre}`); // Añadir mensaje de consola
                    birthdayNames.push(usuario.nombre); // Añadir nombre a la lista
                }
            });

            if (birthdayNames.length > 0) {
                // Construir mensaje para cumpleaños
                const namesString = birthdayNames.map(name => `<strong>${name}</strong>`).join(' y ');
                const messageEnding = birthdayNames.length > 1 ? ' <br>🎂Hoy celebramos la vida y el camino recorrido. Que cada día esté lleno de nuevas oportunidades y felicidad. ¡Gracias por ser la chispa que hace grande a nuestra empresa! 🎁' : '<br>🎂Que este nuevo año te traiga alegrías, éxito y momentos inolvidables. Gracias por ser parte de nuestro equipo y por tu dedicación. ¡Disfruta al máximo tu día especial! 🎁🥳';
                birthdayMessage.innerHTML = `¡Hola, ${namesString}! ${messageEnding}`;
                popup.style.display = 'block';
                fireworks.start(); // Iniciar fuegos artificiales
            } else {
                console.log("Hoy no es el cumpleaños de ningún usuario.");
            }
        })
        .catch(error => console.error('Error:', error));

    // Cerrar el popup y detener los fuegos artificiales cuando se hace clic en la "X"
    closeBtn.onclick = function() {
        popup.style.display = 'none';
        fireworks.stop();
    }

    // Cerrar el popup y detener los fuegos artificiales cuando se hace clic fuera del contenido del popup
    window.onclick = function(event) {
        if (event.target == popup) {
            popup.style.display = 'none';
            fireworks.stop();
        }
    }
});
