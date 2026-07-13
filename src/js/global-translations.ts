type globalTranslationType = {
    confirmation: {
        default_message: string
        ok: string
        cancel: string
    },
    table: {
        filter: string
    },
    validation: {
        required: string
    }
}
declare const ac_global_translations: globalTranslationType;

// The localized global is only enqueued on pages that declare it as a script
// dependency. Degrade to an empty object elsewhere so callers can fall back
// instead of throwing a ReferenceError that breaks the whole render.
export const getGlobalTranslation = (): globalTranslationType => {
    return typeof ac_global_translations !== 'undefined'
        ? ac_global_translations
        : ({} as globalTranslationType);
}