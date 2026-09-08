const TRIGGER_ID = 'ac-admin-nav-more-trigger';
const MENU_ID = 'ac-admin-nav-more';
const ALIGN_END_CLASS = '-align-end';

const initMoreMenu = (): void => {
    const trigger = document.getElementById( TRIGGER_ID ) as HTMLButtonElement | null;
    const menu = document.getElementById( MENU_ID ) as HTMLElement | null;

    if ( !trigger || !menu ) {
        return;
    }

    const isOpen = (): boolean => trigger.getAttribute( 'aria-expanded' ) === 'true';

    // Keep the menu inside the viewport: anchor it to the trigger's inline end when
    // a start-anchored menu would overflow. Both edges are checked, because which
    // one a start-anchored menu runs past depends on the writing direction.
    const align = (): void => {
        menu.classList.remove( ALIGN_END_CLASS );

        const rect = menu.getBoundingClientRect();

        if ( rect.right > document.documentElement.clientWidth || rect.left < 0 ) {
            menu.classList.add( ALIGN_END_CLASS );
        }
    };

    const open = (): void => {
        menu.hidden = false;
        trigger.setAttribute( 'aria-expanded', 'true' );
        align();
    };

    const close = ( returnFocus: boolean = false ): void => {
        menu.hidden = true;
        trigger.setAttribute( 'aria-expanded', 'false' );

        if ( returnFocus ) {
            trigger.focus();
        }
    };

    trigger.addEventListener( 'click', ( event: MouseEvent ) => {
        event.preventDefault();
        event.stopPropagation();

        isOpen() ? close() : open();
    } );

    // Open on hover. The trigger and dropdown share this container, so moving the
    // pointer from one to the other never leaves the container and never closes.
    const container = trigger.closest( '.ac-admin-nav__item' ) as HTMLElement | null;

    if ( container ) {
        container.addEventListener( 'mouseenter', () => {
            if ( !isOpen() ) {
                open();
            }
        } );

        container.addEventListener( 'mouseleave', () => {
            if ( isOpen() ) {
                close();
            }
        } );
    }

    document.addEventListener( 'click', ( event: MouseEvent ) => {
        if ( !isOpen() ) {
            return;
        }

        const target = event.target as Node | null;

        if ( target && !menu.contains( target ) && !trigger.contains( target ) ) {
            close();
        }
    } );

    document.addEventListener( 'keydown', ( event: KeyboardEvent ) => {
        if ( event.key === 'Escape' && isOpen() ) {
            close( true );
        }
    } );

    // Tabbing past the last link closes the menu, so focus order stays linear.
    menu.addEventListener( 'focusout', ( event: FocusEvent ) => {
        const next = event.relatedTarget as Node | null;

        if ( isOpen() && next && !menu.contains( next ) && next !== trigger ) {
            close();
        }
    } );

    window.addEventListener( 'resize', () => {
        if ( isOpen() ) {
            align();
        }
    } );
};

document.addEventListener( 'DOMContentLoaded', initMoreMenu );
