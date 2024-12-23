import AbstractValueEdit from "./AbstractValueEdit";

export default class SimpleValueEdit extends AbstractValueEdit {
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
        this.valueInput = this.addChild('input', {value});

        if (this.param.type == 'int') {
            this.valueInput.type = 'number';
            this.valueInput.step = 1;
        } else if (this.param.type == 'url') {
            this.valueInput.type = 'text';
           } else if (this.param.type == 'boolean') {
            this.valueInput.type = 'checkbox';
            this.valueInput.checked = value === true || value == 'true';
        }
        if (this.param.canFromQuery) {
            this.queryCheckbox = this.addChild('input', {
                type: 'checkbox',
                title: 'allow from url'
            });
            this.queryCheckbox.checked = this.paramConfig?.source == 'query';
        }
    }

    collectParameters() {
        return {source: this.queryCheckbox?.checked ? 'query' : 'const', value: this.valueInput.value};
    }
}
customElements.define('simple-value-edit', SimpleValueEdit);
