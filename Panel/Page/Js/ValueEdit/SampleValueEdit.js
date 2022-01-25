import AbstractValueEdit from "./AbstractValueEdit";

export default class SampleValueEdit extends AbstractValueEdit {
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
customElements.define('sample-value-edit', SampleValueEdit);