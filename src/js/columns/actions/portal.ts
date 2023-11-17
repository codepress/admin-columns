import {tick} from "svelte";

const portal_map = new Map();

export const createPortal = (node: Node, id = "default") => {
    const key = `$$portal.${id}`;
    if (portal_map.has(key)) throw `duplicate portal key "${id}"`;
    else portal_map.set(key, node);
    return {destroy: portal_map.delete.bind(portal_map, key)};
}

function mount(node: Node, key: string) {
    if (!portal_map.has(key)) throw `unknown portal ${key}`;
    const host = portal_map.get(key);
    host.insertBefore(node, null);
    return () => host.contains(node) && host.removeChild(node);
}

export function portal(node: Node, id: string = "default") {
    let destroy: Function;
    const key = `$$portal.${id}`;
    if (!portal_map.has(key))
        tick().then(() => {
            destroy = mount(node, key);
        });
    else destroy = mount(node, key);
    return {destroy: () => destroy?.()};
}
