import AbstractValueEdit from "./AbstractValueEdit";
import StructValueEdit from "./StructValueEdit";

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
                value: JSON.stringify([component.package, component.name]),
                text: (component.package || '') + '\\' + component.name
            });
        }
        this.componentSelect.value = JSON.stringify([this.paramConfig?.value?.module, this.paramConfig?.value?.component]);
        this.sub=document.createElement('div');
        this.append(this.sub);
        this.drawSubComponent()
    }
    drawSubComponent(){
        if(this.componentSelect.value) {
            const [pack, name] = JSON.parse(this.componentSelect.value)
            const componentDef = this.availableComponents.find(x => x.package === pack && x.name === name);
            const valueEdit = new StructValueEdit(this.paramConfig?.value?.params, {items:componentDef.definedParameters});
            this.sub.append(valueEdit);
            valueEdit.draw();
        }
    }

    collectParameters() {
        const [module, component] = JSON.parse(this.componentSelect.value)
        return {value: {module, component, params: this.sub.firstElementChild?.collectParameters()}}
    }
}
customElements.define('component-value-edit', ComponentValueEdit);
