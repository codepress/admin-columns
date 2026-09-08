import {initDismissibleNotices} from "./plugin/dismissible-notice";
import {initReviewNotice} from "./plugin/review-notice";

document.addEventListener('DOMContentLoaded', () => {
    initDismissibleNotices();
    initReviewNotice();
});