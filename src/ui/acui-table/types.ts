import {ComponentType} from "svelte";

export type DataTableFieldDefinitionType = {
    id: string,
    label?: string,
    type?: 'text' | 'number',
    width?: number,
    numeric?: boolean,
    getValue?: (item: any) => string
    render?: ComponentType
}

export type DataTableActionsDefinitionType = {
    id: string,
    label: string,
    callback?: (item: any) => void,
    primary?: boolean
}