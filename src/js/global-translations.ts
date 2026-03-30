type globalTranslationType = {
    confirmation: {
        default_message: string
        ok: string
        cancel: string
    },
    table: {
        filter: string
    }
}
declare const ac_global_translations: globalTranslationType;

export const getGlobalTranslation = (): globalTranslationType => {
    return ac_global_translations;
}