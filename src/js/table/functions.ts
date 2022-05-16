import {Tooltip} from "../plugin/tooltip";

export const init_actions_tooltips = () => {
    document.querySelectorAll('.cpac_use_icons').forEach((el: HTMLElement) => {
        el?.parentElement?.querySelectorAll('.row-actions a').forEach((el: HTMLElement) => {
            new Tooltip(el, el.innerText);
        });
    });

}