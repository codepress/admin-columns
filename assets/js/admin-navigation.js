/******/ (() => { // webpackBootstrap
/*!********************************!*\
  !*** ./js/admin-navigation.ts ***!
  \********************************/
const TRIGGER_ID = 'ac-admin-nav-more-trigger';
const MENU_ID = 'ac-admin-nav-more';
const ALIGN_RIGHT_CLASS = '-align-right';
const initMoreMenu = () => {
    const trigger = document.getElementById(TRIGGER_ID);
    const menu = document.getElementById(MENU_ID);
    if (!trigger || !menu) {
        return;
    }
    const isOpen = () => trigger.getAttribute('aria-expanded') === 'true';
    // Keep the menu inside the viewport: anchor it to the trigger's right edge when
    // a left-anchored menu would overflow.
    const align = () => {
        menu.classList.remove(ALIGN_RIGHT_CLASS);
        if (menu.getBoundingClientRect().right > document.documentElement.clientWidth) {
            menu.classList.add(ALIGN_RIGHT_CLASS);
        }
    };
    const open = () => {
        menu.hidden = false;
        trigger.setAttribute('aria-expanded', 'true');
        align();
    };
    const close = (returnFocus = false) => {
        menu.hidden = true;
        trigger.setAttribute('aria-expanded', 'false');
        if (returnFocus) {
            trigger.focus();
        }
    };
    trigger.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();
        isOpen() ? close() : open();
    });
    // Open on hover. The trigger and dropdown share this container, so moving the
    // pointer from one to the other never leaves the container and never closes.
    const container = trigger.closest('.ac-admin-nav__item');
    if (container) {
        container.addEventListener('mouseenter', () => {
            if (!isOpen()) {
                open();
            }
        });
        container.addEventListener('mouseleave', () => {
            if (isOpen()) {
                close();
            }
        });
    }
    document.addEventListener('click', (event) => {
        if (!isOpen()) {
            return;
        }
        const target = event.target;
        if (target && !menu.contains(target) && !trigger.contains(target)) {
            close();
        }
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isOpen()) {
            close(true);
        }
    });
    // Tabbing past the last link closes the menu, so focus order stays linear.
    menu.addEventListener('focusout', (event) => {
        const next = event.relatedTarget;
        if (isOpen() && next && !menu.contains(next) && next !== trigger) {
            close();
        }
    });
    window.addEventListener('resize', () => {
        if (isOpen()) {
            align();
        }
    });
};
document.addEventListener('DOMContentLoaded', initMoreMenu);

/******/ })()
;
//# sourceMappingURL=admin-navigation.js.map