export const getParamFromUrl = (param: string, url: string) => {
    if (!url.includes('?')) {
        return null;
    }

    const params = new URLSearchParams(url.split('?')[1]);

    return params.get(param);
}

export const mapDataToFormData = (data: any, formData: FormData = null): FormData => {
    if (!formData) {
        formData = new FormData();
    }

    Object.keys(data).forEach(key => {
        let value = data[key];
        if (Array.isArray(value)) {
            value.forEach(d => {
                formData.append(`${key}[]`, d);
            });
        } else {
            formData.append(key, data[key]);
        }
    });

    return formData;
}