import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [];


    connect() {
        let filter = document.getElementsByName('filter-datas');
        let value = '';
        filter.forEach(element => {
            element.addEventListener('change', function () {
                console.log('hello');
                if (element.checked) {
                    value = element.value;
                    if (value != '') {
                        fetch('/admin/get-list/' + value)
                            .then(data => data.text())
                            .then(html => {
                                document.getElementById('include-select').innerHTML = html;
                            })
                    }
                }
            })
        });

    }


}
