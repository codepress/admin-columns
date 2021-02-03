import {fadeIn, fadeOut} from "../../helpers/animations";

class Feedback {

    element: HTMLElement

    constructor(element: HTMLElement) {
        this.element = element;
        this.init();
    }

    init() {
        this.element.querySelector('a.no').addEventListener('click', (e) => {
            e.preventDefault();
            fadeOut(this.element.querySelector('#feedback-choice'), 300, () => {
                fadeIn(this.element.querySelector('#feedback-support'), 300);
            });
        });

        this.element.querySelector('a.yes').addEventListener('click', (e) => {
            e.preventDefault();
            fadeOut(this.element.querySelector('#feedback-choice'), 300, () => {
                fadeIn(this.element.querySelector('#feedback-rate'), 300);
            });
        });
    };

}

export default Feedback;