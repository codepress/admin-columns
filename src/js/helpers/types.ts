export type keyStringPair = { [key: string]: string }
export type keyAnyPair = { [key: string]: any }
export type keySpecificPair<T = any> = { [key: string]: T }