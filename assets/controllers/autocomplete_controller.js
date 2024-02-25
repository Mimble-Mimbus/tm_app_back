import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [];


    connect() {
        this.addOptions;
        let addLink = this.element.parentNode.parentNode.nextSibling('.field-collection-add-button');
        addLink.addEventListener('click', function() {
            this.addOptions;
        })
        
    }

    addOptions() {
        let inputs = this.element.querySelectorAll('input');
        console.log(inputs.length);

        for (let input of inputs) {
            input.addEventListener('keyup', function () {
                if (input.value.length >= 3) {
                    console.log('hiiiiiii');
                }
            });
        }

    }


}
