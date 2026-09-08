import {addEventListenerLive} from "../helpers/events";
import {postDismissCallback} from "./dismissible-notice";

// Swaps the review request for the help panel and does not ask again
export const initReviewNotice = () => {
    addEventListenerLive('click', 'a.hide-review-notice-soft', function (this: HTMLElement, e: Event) {
        e.preventDefault();

        const notice = this.closest<HTMLElement>('.ac-notice');

        if (!notice) {
            return;
        }

        notice.querySelector('.info')?.remove();
        notice.querySelector('.help')?.classList.remove('hidden');

        postDismissCallback(notice);
    });
}
