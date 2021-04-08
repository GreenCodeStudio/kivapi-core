export default class ActiveElementList {
    constructor(wrapper, list = []) {
        this.wrapper = wrapper;
        this._list = list;
        this._old = new WeakMap();
    }

    get list() {
        return list;
    }

    set list(v) {
        this._list = v;
    }

    key(x) {
        return x;
    }

    item(data, old) {
        return data;
    }

    draw() {
        let oldChildren = Array.from(this.wrapper.childNodes);
        let newChildren = [];
        for (let x of this._list) {
            let key = this.key(x);
            let node;
            if (this._old.has(key)) {
                node = this._old.get(key);
            } else {
                node = this.item(x);
                this._old.set(key, node);
            }
            if (node)
                newChildren.push(node);
        }
        for (let i = 0, n = 0; i < this.wrapper.childNodes.length || n < newChildren.length;) {
            if (this.wrapper.childNodes[i] === newChildren[n]) {
                i++;
                n++;
            } else if (n < newChildren.length) {
                this.wrapper.insertBefore(newChildren[n], this.wrapper.childNodes[i]);
                i++;
                n++;
            } else {
                this.wrapper.removeChild(this.wrapper.childNodes[i]);
            }
        }
    }
}