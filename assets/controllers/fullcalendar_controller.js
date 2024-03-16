import { Controller } from '@hotwired/stimulus';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';

export default class extends Controller {

    static targets = ['event', 'zone', 'volunteer'];

    connect() {
        this.calendar();
        // this.set_filters();
    }

    calendar() {
        let url = '/admin/ajax/get_calendar/?';
        
        if (this.eventTarget.value != "") {
            url += `&event=${this.eventTarget.value}`;
        }

        if (this.zoneTarget.value != "") {
            url += `&zone=${this.zoneTarget.value}`;
        }

        if (this.volunteerTarget.value != "") {
            url += `&volunteer=${this.volunteerTarget.value}`;
        }


        fetch(url)
        .then(response => response.json())
        .then(data => {

            let calendarEl = document.getElementById('calendar');
            if (data.start == null) {
                calendarEl.innerHTML = "Aucun événement ne correspond aux filtres.";
            } else {

                let calendar = new Calendar(calendarEl, {
                    plugins: [ dayGridPlugin, timeGridPlugin, listPlugin ],
                    initialView: 'dayGridWeek',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listWeek'
                    },
                    initialDate: data.start,
                    events: data.events,
                    firstDay: 1,
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
            }
        });

        this.set_filters();
    }

    set_filters(){
        let url = '/admin/ajax/set_filters/?';

        if (this.eventTarget.value != "") {
            url += `&event=${this.eventTarget.value}`;
        }

        if (this.zoneTarget.value != "") {
            url += `&zone=${this.zoneTarget.value}`;
        }

        if (this.volunteerTarget.value != "") {
            url += `&volunteer=${this.volunteerTarget.value}`;
        }

        fetch(url)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            let filtres = doc.querySelector('#planning_filters');
            document.getElementById('planning_filters').replaceWith(filtres);
        })
    }
}
