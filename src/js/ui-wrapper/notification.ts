import AcNotification from "ACUi/acui-notification/AcNotification.svelte";
import AcHtmlElement from "../helpers/html-element";

const SELECTOR = 'acui-notifications';

type notificationArguments = {
    message: string,
    type?: 'success' | 'warning' | 'notify' | 'default' | 'error';
    duration?: number
    closable?: boolean
    autoClose?: boolean
    active?: boolean
}

export class NotificationProgrammatic {

    static open(args: notificationArguments | string) {
        const container = document.createElement('div')!;

        if (typeof args === 'string') {
            args = {
                message: args
            }
        }

        const finalArgs = Object.assign({
            autoClose: true
        }, args)

        initNotificationContainer().append(container);


        const notification = new AcNotification({
            target: container,
            // @ts-ignore
            props: finalArgs,
        });

        notification.$on('close', () => {
            notification.$destroy();
        })
    }

}

const initNotificationContainer = (): HTMLElement => {
    let container = document.querySelector<HTMLElement>(`.${SELECTOR}`);


    if (container) {
        return container;
    }

    const newContainer = AcHtmlElement.create('div').addClass(SELECTOR).getElement();

    document.body.append(newContainer);

    return newContainer;
}
