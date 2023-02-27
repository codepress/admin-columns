import AcuiToggleButtonsWrapper from "./toggle-buttons";

export const initUiToggleButtons = () => {
    document.querySelectorAll<HTMLInputElement>('input[data-component="acui-toggle-buttons"]').forEach(input => {
        new AcuiToggleButtonsWrapper(input);
    });
}

