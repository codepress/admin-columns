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
    status: string
    columns: ListScreenColumnData[]
    id: string
    settings: ListScreenPreferenceData
    title: string
    type: string
    updated: number
}