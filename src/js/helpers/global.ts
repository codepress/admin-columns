export const getParamFromUrl = (param: string, url: string) => {
    param = param.replace(/[\[\]]/g, "\\$&");

    let regex = new RegExp("[?&]" + param + "(=([^&#]*)|&|#|$)"), results = regex.exec(url);

    if (!results) {
        return null;
    }

    if (!results[2]) {
        return '';
    }

    return decodeURIComponent(results[2].replace(/\+/g, " "));
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