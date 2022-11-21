export const getParamFromUrl = (param: string, url: string): string | null => {
    if (!url.includes('?')) {
        return null;
    }

    const params = new URLSearchParams(url.split('?')[1]);

    return params.get(param);
}

export const mapDataToFormData = (data: any, formData: FormData | null = null): FormData => {
    let fData: FormData = formData ?? new FormData();

    Object.keys(data).forEach(key => {
        appendObjectToFormData(fData, data[key], key);
    });

    return fData;
}

export const appendObjectToFormData = (formData: FormData, data: { [key: string]: any }, parentKey: string = '') => {
    if (data && typeof data === 'object' && !(data instanceof Date) && !(data instanceof File)) {
        Object.keys(data).forEach(key => {
            appendObjectToFormData(formData, data[key], parentKey ? `${parentKey}[${key}]` : key);
        });
    } else {
        const value = data == null ? '' : data;

        formData.append(parentKey, value as string);
    }
}

export const sanitizeColumnSelector = ( name: string ) => {
    return name.replace(/\./g, '\\.');
}