import AbstractValueEdit from "./AbstractValueEdit";
import {t as TCommon} from "../../../Common/i18n.xml";
import {generateParam} from "./ValueEditFactory";
import {areIdentical} from "../Utils";
import DragAndDropHandler from "./DragAndDropHandler";

export default class ArrayValueEdit extends AbstractValueEdit {
    constructor(paramConfig, param) {
        super(paramConfig, param);
    }

    draw() {
        super.draw();
        this.paramData = []
        let array = this.paramConfig?.value || []
        if (!(array instanceof Array)) array = [];
        for (let i = 0; i < array.length; i++) {
            let childConfig = array[i];
            let result = this.drawItem(i, childConfig);
            this.paramData.push(result)
        }
        this.addButton = this.addChild('div').addChild('button', {type: 'button', text: TCommon('Add')});
        this.addButton.onclick = () => {
            let label = document.create('.label');
            let result = this.drawItem(this.paramData.length, [])
            this.paramData.push(result)
        }
    }

    drawItem(i, childConfig) {
        let result = generateParam(this.param.item, childConfig);
        let label = document.create('.label', {draggable: "true"});
        label.addChild('span', {text: i + 1 + '.'});
        label.append(result.node);
        label.ondragstart = result.node.dragstartHandler?.bind(result.node);

        let removeButton = label.addChild('div').addChild('button', {text: 'Usuń'})
        removeButton.onclick = result.removeFromParent =result.node.removeFromParent = () => {
            this.removeItem(result);
        }
        this.insertBefore(label, this.children[i]);
        if (this.param.type == 'tree') {
            let subLabel = document.create('.label', {draggable: true});
            subLabel.addChild('span', {text: 'child'});
            let subResult = generateParam(this.param, childConfig?.children ?? null);
            subLabel.append(subResult.node);
            result.node.append(subLabel);
            const oldCollectParameters = result.collectParameters
            result.collectParameters = () => ({
                ...oldCollectParameters(),
                children: {type: this.param.type, ...subResult.collectParameters()}
            })
        }
        return result;
    }

    collectParameters() {
        return {value: this.paramData.map(x => ({type: this.param.item.type, ...x.collectParameters()}))}
    }

    tryAcceptChild(data, path) {
        console.log('tryAcceptChild')
        if (areIdentical(this.param.item, data.param)) {
            let label = path[path.indexOf(this) - 1];
            let index = Array.from(this.children).indexOf(label);
            let result = this.drawItem(index, data.paramConfig);
            this.paramData.splice(index, 0,result)
            this.updateNumbers();
            return true;
        } else
            return false;
    }

    removeItem(item) {
        item.node.parentNode.remove();
        let index = this.paramData.indexOf(item);
        this.paramData.splice(index, 1);
        this.updateNumbers();
    }
    updateNumbers(){
        for (let i = 0; i < this.paramData.length; i++) {
            this.paramData[i].node.parentNode.children[0].textContent = i + 1 + '.';
        }
    }
}
customElements.define('array-value-edit', ArrayValueEdit);
