// scripts.js
document.addEventListener('DOMContentLoaded', function () {
    const newsContainer = document.getElementById('news-container');

    // Simulando datos de noticias (puedes reemplazarlo con una llamada AJAX para obtener datos de un servidor)
    const newsData = [
        {
            title: "Cumpleaños Mes de Junio - Sede Benavides",
            date: '2024-06-30',
            content: '¡Feliz cumpleaños a todos los que celebran su día en junio! Les deseamos un año lleno de éxitos, alegría y momentos inolvidables. Gracias por ser parte esencial de nuestro equipo.',
            imageUrl: '../images/news/Cumple mes Junio - Sede Benavides.jpeg'
        },
        {
            title: "Cumpleaños Mes de Junio - Sede Panamá",
            date: '2024-06-30',
            content: '¡Feliz cumpleaños a todos los que celebran su día en junio! Les deseamos un año lleno de éxitos, alegría y momentos inolvidables. Gracias por ser parte esencial de nuestro equipo.',
            imageUrl: '../images/news/Cumple mes Junio - Sede Panamá.jpeg'
        },
        {
            title: "Cumpleaños Mes de Junio - Sede San Isidro",
            date: '2024-06-30',
            content: '¡Feliz cumpleaños a todos los que celebran su día en junio! Les deseamos un año lleno de éxitos, alegría y momentos inolvidables. Gracias por ser parte esencial de nuestro equipo.',
            imageUrl: '../images/news/Cumple mes Junio - Sede San Isidro.jpeg'
        },
        {
            title: "Father's Day (Día del Padre)",
            date: '2024-06-16',
            content: 'En este Día del Padre, queremos extender nuestro más sincero agradecimiento y felicitaciones a todos los padres de nuestro equipo. Gracias por su dedicación, esfuerzo y por ser una fuente constante de inspiración. ¡Que tengan un día lleno de amor y felicidad junto a sus seres queridos!',
            imageUrl: '../images/news/Feliz día del Padre!.jpeg'
        },
        {
            title: "Cumpleaños Mes de Mayo - Sede Benavides",
            date: '2024-05-31',
            content: '¡Feliz cumpleaños a todos los que celebran su día en mayo! Les deseamos un año lleno de éxitos, alegría y momentos inolvidables. Gracias por ser parte esencial de nuestro equipo.',
            imageUrl: '../images/news/Cumple mes Mayo - Sede Benavides.jpeg'
        },
        {
            title: "Mother's Day(Día de la Madre)",
            date: '2024-05-12',
            content: 'En este Día de la Madre, queremos rendir homenaje a todas las madres que, con su amor y dedicación, inspiran y fortalecen a nuestras familias y comunidades. Gracias por ser el corazón y el alma de nuestro mundo. ¡Feliz Día de la Madre!',
            imageUrl: '../images/news/diamadre.jpeg'
        },
        {
            title: "Cumpleaños Mes de Abril - Sede Benavides",
            date: '2024-04-30',
            content: '¡Feliz cumpleaños a todos los que celebran su día en abril! Les deseamos un año lleno de éxitos, alegría y momentos inolvidables. Gracias por ser parte esencial de nuestro equipo.',
            imageUrl: '../images/news/Cumplemes hasta abril - Sede Benavides.jpeg'
        },
        {
            title: "International Women's Day (Día Internacional de la Mujer)",
            date: '2024-03-08',
            content: 'En este Día Internacional de la Mujer, ITL y Claimpay celebran y honran la fuerza, la dedicación y el talento de todas las mujeres que hacen posible nuestro éxito. Juntas, continuamos construyendo un futuro más brillante y equitativo. ¡Feliz Día de la Mujer!',
            imageUrl: '../images/news/diamujer.jpeg'
        },



        // Agrega más noticias aquí
    ];

    // Función para renderizar noticias
    function renderNews(news, index) {
        return `
            <div class="carousel-item ${index === 0 ? 'active' : ''}">
                <div class="news-item">
                    <img class="news-image" src="${news.imageUrl}" alt="${news.title}">
                    <div class="news-content">
                        <h3 class="news-title">${news.title}</h3>
                        <p class="news-date">${news.date}</p>
                        <p class="news-text">${news.content}</p>
                    </div>
                </div>
            </div>
        `;
    }

    // Renderizando todas las noticias
    newsData.forEach((news, index) => {
        newsContainer.innerHTML += renderNews(news, index);
    });
});


// scripts.js
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        events: function(fetchInfo, successCallback, failureCallback) {
            fetch('get_events.php')
                .then(response => response.json())
                .then(data => successCallback(data))
                .catch(error => failureCallback(error));
        }
    });

    calendar.render();
});
