type globalTranslationType = {
    confirmation: {
        ok: string
        cancel: string
    }
}
declare const ac_global_translations: globalTranslationType;

export const getGlobalTranslation = (): globalTranslationType => {
    return ac_global_translations;
}