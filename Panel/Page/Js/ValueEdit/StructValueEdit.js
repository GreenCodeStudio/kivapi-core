import AbstractValueEdit from "./AbstractValueEdit";
import {generateParam} from "./ValueEditFactory";

export default class StructValueEdit extends AbstractValueEdit {
    constructor(paramConfig, param) {
        super(paramConfig, param);
    }

    draw() {
        super.draw();
        //const node = document.create('div.subgroup')
        this.paramData = []
        for (let childName in this.param.items) {
            let child = this.param.items[childName];
            let label = document.create('.label');
            label.addChild('span', {text: childName, draggable:true});
            let childConfig = this.paramConfig?.value[childName];
            let result = generateParam(child, childConfig);
            label.ondragstart=result.node.dragstartHandler?.bind(result);
            label.append(result.node);
            this.append(label);
            this.paramData.push({name: childName, param: child, ...result});
        }
    }

    collectParameters() {
        return ({
            value: Object.fromEntries( this.paramData.map(x => {
                return [x.name, {type: x.param.type, ...x.collectParameters()}];
            }))
        });
    }
}
customElements.define('struct-value-edit', StructValueEdit);