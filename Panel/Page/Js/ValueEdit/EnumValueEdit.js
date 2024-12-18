import AbstractValueEdit from "./AbstractValueEdit";

export default class EnumValueEdit extends AbstractValueEdit {
    constructor(paramConfig, param) {
        super(paramConfig, param);
    }

    draw() {
        super.draw();
        let value
        if (typeof (this.paramConfig?.value) == 'string') {
            value = this.paramConfig?.value
        } else {
            value = this.param.default ?? '';
        }
        this.valueInput = this.addChild('select', {children: this.param.values.map(x => {
            return {tagName: 'option', text: x, value: x, selected: x == value}
        })});

    }

    collectParameters() {
        return {source: this.queryCheckbox?.checked ? 'query' : 'const', value: this.valueInput.value};
    }
}
customElements.define('enum-value-edit', EnumValueEdit);
