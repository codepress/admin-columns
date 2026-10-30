import {NonceData} from "../types/requests";

export const nonceParam = (nonce: NonceData): Record<string, string> => ({[nonce.name]: nonce.value});
