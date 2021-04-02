export class I18nNode {
    constructor(values, childNodes) {
        this._values = new Map()
        for (let {lang, value} of values) {
            this.addValue(lang, value);
        }
        this._children = new Map()
        for (let {name, node} of childNodes) {
            this.addChild(name, node);
        }
    }

    addChild(name, node) {
        this._children.set(name, node);
    }

    addValue(lang, value) {
        this._values.set(lang, value);
    }

    getChild(name) {
        if (this._children.has(name))
            return this._children.get(name);
        else
            return null;
    }

    getValue(languageHierarchy) {
        let bestLang = languageHierarchy.pickBest(Array.from(this._values.keys()));
        return this._values.get(bestLang);
    }
}