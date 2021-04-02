import AbstractValueEdit from "./AbstractValueEdit";

export default class ComponentValueEdit extends AbstractValueEdit {
    constructor(paramConfig, param, availableComponents) {
        super(paramConfig, param);
        this.availableComponents = availableComponents;
    }

    draw() {
        super.draw();
        this.componentSelect = this.addChild('select');
        for (let component of this.availableComponents) {
            this.componentSelect.addChild('option', {
                value: JSON.stringify(component),
                text: (component[0] || '') + '\\' + component[1]
            });
        }
        this.componentSelect.value = JSON.stringify([this.paramConfig?.value.module, this.paramConfig?.value.component]);
    }

    collectParameters() {
        const [module, component] = JSON.parse(this.componentSelect.value)
        return {value: {module, component, params: {}}}
    }
}
customElements.define('component-value-edit', ComponentValueEdit);