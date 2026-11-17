const SAFE_SCHEME = /^(?:https?|mailto|tel|ftp):/i;

// Returns the url unchanged when it is relative or uses a safe scheme, and an
// empty string when it carries an unsafe scheme such as javascript: or data:.
// Control characters and whitespace are stripped before the scheme is read
// because the browser ignores them when resolving the scheme of an href.
export const sanitizeUrl = (url: string): string => {
    if (!url) {
        return '';
    }

    const stripped = url.replace(/[\u0000-\u001F\u007F-\u009F\s]+/g, '');
    const scheme = stripped.match(/^([a-z][a-z0-9+.-]*):/i);

    if (scheme && !SAFE_SCHEME.test(stripped)) {
        return '';
    }

    return url;
}
