export function areIdentical(a, b) {
    if (a == b) return true;
    else if (a instanceof Array && b instanceof Array) {
        if (a.length != b.length)
            return false;
        for (let i = 0; i < a.length; i++) {
            if (!areIdentical(a[i], b[i]))
                return false;
        }
        return true;
    } else if (a instanceof Object && b instanceof Object) {
        let aKeys = Object.keys(a);
        let bKeys = Object.keys(b);
        if (!areIdentical(aKeys.sort(), bKeys.sort())) return false;
        for (let key of aKeys) {
            if (!areIdentical(a[key], b[key]))
                return false
        }
        return true;
    } else {
        return false;
    }
}