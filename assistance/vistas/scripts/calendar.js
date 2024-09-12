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
                    console.log('Eventos:', data);
                    fetch('get_birthdays.php')
                        .then(response => response.json())
                        .then(birthdays => {
                            console.log('Cumpleaños:', birthdays);
                            successCallback(data.concat(birthdays));
                        })
                        .catch(error => {
                            console.error('Error obteniendo cumpleaños:', error);
                            failureCallback(error);
                        });
                })
                .catch(error => {
                    console.error('Error obteniendo eventos:', error);
                    failureCallback(error);
                });
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
            var message = document.getElementById('message');

            modalTitle.textContent = eventObj.title;
            editTitle.value = eventObj.title;
            editStart.value = eventObj.startStr.slice(0, 10);
            editColor.value = eventObj.backgroundColor;

            // Verificar si es un cumpleaños
            if (eventObj.title.includes('Cumpleaños')) {
                modalDeleteButton.style.display = 'none';
                modalEditButton.style.display = 'none';
                message.textContent = 'No se puede borrar ni editar los cumpleaños.';
                message.style.display = 'block';
                alert('No se puede borrar ni editar los cumpleaños.');
            } else {
                modalDeleteButton.style.display = 'block';
                modalEditButton.style.display = 'block';
                message.style.display = 'none';

                modalDeleteButton.onclick = function() {
                    if (confirm('Are you sure you want to delete this event?')) {
                        fetch('delete_event.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ id: eventObj.id })
                        }).then(response => {
                            if (response.ok) {
                                eventObj.remove();
                                alert('Event removed successfully');
                                modal.style.display = 'none';
                            } else {
                                alert('Failed to remove event');
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
                            eventObj.setEnd(newStart);
                            eventObj.setProp('backgroundColor', newColor);
                            calendar.refetchEvents();
                            alert('Event updated successfully');
                            modal.style.display = 'none';
                        } else {
                            alert('Failed to update event');
                        }
                    });
                };
            }

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
