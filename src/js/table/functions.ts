// @ts-ignore
import $ from 'jquery';

export const init_actions_tooltips = () => {
    $('.cpac_use_icons').parent().find('.row-actions a').qtip({
        content: {
            text: function () {
                return $(this).text();
            }
        },
        position: {
            my: 'top center',
            at: 'bottom center'
        },
        style: {
            tip: true,
            classes: 'qtip-tipsy'
        }
    });
}