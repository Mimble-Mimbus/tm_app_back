import { Controller } from '@hotwired/stimulus';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';

export default class extends Controller {

    static values = { filter: String, id: Number }

    connect() {

        fetch('/admin/ajax/get_calendar/?filter=' + this.filterValue + '&id=' + this.idValue)
        .then(response => response.json())
        .then(data => {
            
            let calendarEl = this.element;
            let calendar = new Calendar(calendarEl, {
                plugins: [ dayGridPlugin, timeGridPlugin, listPlugin ],
                initialView: 'dayGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: data.events,
                locale: 'fr',
                buttonText:{                
                    today:    'auj.',
                    month:    'mois',
                    week:     'semaine',
                    day:      'jour',
                    list:     'liste'
                }
                  
            });
            calendar.render();
        })
        ;

    }
}
