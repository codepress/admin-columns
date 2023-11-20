export type AcSelectValue = {
    [key: string]: {
        title: string
        options: {
            [key: string]: string
        }
    }
}

type AcRemoteSelectGroup = {
    text: string,
    children: AcRemoteSelectValue[]
}
type AcRemoteSelectValue = {
    text: string,
    id: number | string,
}

export type AcRemoteSelectValues = Array<AcRemoteSelectValue | AcRemoteSelectGroup>

export interface SvelteSelectItem {
    value: string | number
    label: string
    group?: string|null
}

export interface SvelteSelectValueItem extends SvelteSelectItem {
    groupItem?: boolean
}