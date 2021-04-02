import AbstractValueEdit from "./AbstractValueEdit";

export default class SampleValueEdit extends AbstractValueEdit {
    constructor(paramConfig, param) {
        super(paramConfig, param);
    }

    draw() {
        super.draw();
        this.valueInput = this.addChild('input', {value: this.paramConfig?.value ?? this.param.default ?? ''});
        this.queryCheckbox = this.addChild('input', {
            type: 'checkbox',
            title: 'allow from url'
        });
        this.queryCheckbox.checked = this.paramConfig?.source == 'query';
    }

    collectParameters() {
        return {source: this.queryCheckbox.checked ? 'query' : 'const', value: this.valueInput.value};
    }
}
customElements.define('sample-value-edit', SampleValueEdit);