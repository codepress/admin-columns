export interface ListScreenColumnData {
    label: string
    name: string
    type: string

    [key: string]: any
}

export interface ListScreenColumnsData {
    [key: string]: ListScreenColumnData
}

export interface ListScreenPreferenceData {
    [key: string]: any
}

export interface ListScreenData {
    columns: ListScreenColumnsData
    id: string
    settings: ListScreenPreferenceData
    title: string
    type: string
    updated: number
}