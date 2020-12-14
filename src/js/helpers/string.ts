export const uniqid = (a = "", b = false): string => {
    const c = Date.now() / 1000;
    let d = c.toString(16).split(".").join("");
    while (d.length < 14) d += "0";
    let e = "";
    if (b) {
        e = ".";
        e += Math.round(Math.random() * 100000000);
    }
    return a + d + e;
}

export const stripHtml = (originalString: string): string => {
    return originalString.replace(/(<([^>]+)>)/gi, "");
}