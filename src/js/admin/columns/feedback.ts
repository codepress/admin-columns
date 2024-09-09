import {fadeIn, fadeOut} from "../../helpers/animations";

//TODO implement feedback
class Feedback {

    constructor(public element: HTMLElement) {
        this.init();
    }

    init() {
        this.element.querySelector('a.no')?.addEventListener('click', (e) => {
            e.preventDefault();
            fadeOut(this.element.querySelector('#feedback-choice')!, 300, () => {
                fadeIn(this.element.querySelector('#feedback-support')!, 300);
            });
        });

        this.element.querySelector('a.yes')?.addEventListener('click', (e) => {
            e.preventDefault();
            fadeOut(this.element.querySelector('#feedback-choice')!, 300, () => {
                fadeIn(this.element.querySelector('#feedback-rate')!, 300);
            });
        });
    };

}

export default Feedback;