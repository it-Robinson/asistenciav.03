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
                .then(data => {
                    fetch('get_birthdays.php')
                        .then(response => response.json())
                        .then(birthdays => {
                            successCallback(data.concat(birthdays));
                        })
                        .catch(error => failureCallback(error));
                })
                .catch(error => failureCallback(error));
        },
        eventClick: function(info) {
            var eventObj = info.event;
            var modal = document.getElementById('eventModal');
            var modalTitle = document.getElementById('modalTitle');
            var editTitle = document.getElementById('editTitle');
            var editStart = document.getElementById('editStart');
            var editColor = document.getElementById('editColor');
            var modalDeleteButton = document.getElementById('deleteButton');
            var modalEditButton = document.getElementById('editButton');

            modalTitle.textContent = eventObj.title;
            editTitle.value = eventObj.title;
            editStart.value = eventObj.startStr.slice(0, 10); // Asegurarse de usar solo la parte de la fecha
            editColor.value = eventObj.backgroundColor;

            modalDeleteButton.onclick = function() {
                if (confirm('¿Estás seguro de que quieres borrar este evento?')) {
                    fetch('delete_event.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id: eventObj.id })
                    }).then(response => {
                        if (response.ok) {
                            eventObj.remove();
                            alert('Evento eliminado con éxito');
                            modal.style.display = 'none';
                        } else {
                            alert('No se ha podido eliminar el evento');
                        }
                    });
                }
            };

            modalEditButton.onclick = function() {
                var newTitle = editTitle.value;
                var newStart = editStart.value;
                var newColor = editColor.value;

                fetch('edit_event.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: eventObj.id, title: newTitle, start: newStart, color: newColor })
                }).then(response => {
                    if (response.ok) {
                        eventObj.setProp('title', newTitle);
                        eventObj.setStart(newStart);
                        eventObj.setEnd(newStart); // Asegurarse de actualizar correctamente las fechas
                        eventObj.setProp('backgroundColor', newColor);
                        calendar.refetchEvents(); // Refrescar los eventos del calendario para asegurar la actualización
                        alert('Evento actualizado correctamente');
                        modal.style.display = 'none';
                    } else {
                        alert('Error al actualizar el evento');
                    }
                });
            };

            modal.style.display = 'block';
        }
    });

    calendar.render();

    // Close modal when clicking outside
    var modal = document.getElementById('eventModal');
    var span = document.getElementsByClassName('close')[0];
    span.onclick = function() {
        modal.style.display = 'none';
    }
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };
});
