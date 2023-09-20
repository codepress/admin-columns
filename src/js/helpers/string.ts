export const uniqid = (prefix = "", moreEntropy = false): string => {

    const c = Math.floor(Math.random() * Date.now()) / 1000;
    let d = c.toString(16).split(".").join("");
    while (d.length < 14) d += "0";
    let e = "";
    if (moreEntropy) {
        e = ".";
        e += Math.round(Math.random() * 100000000);
    }

    return prefix + d + e;
}

export const stripHtml = (originalString: string): string => {
    return originalString ? originalString.replace(/(<([^>]+)>)/gi, "") : '';
}