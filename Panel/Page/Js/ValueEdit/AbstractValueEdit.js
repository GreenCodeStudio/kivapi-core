import DragAndDropHandler from "./DragAndDropHandler";

export default class AbstractValueEdit extends HTMLElement {
    constructor(paramConfig, param) {
        super();
        this.paramConfig = paramConfig;
        this.param = param;
    }

    draw() {
        this.addEventListener('drop', e => {
            let dataJSON = e.dataTransfer.getData('text/cms-value-params');
            if (dataJSON) {
                let data = JSON.parse(dataJSON)
                if (this.tryAcceptChild(data, e.composedPath())) {

                    e.stopPropagation();
                    e.preventDefault();
                    if (!e.ctrlKey && DragAndDropHandler.dragCounter == data.dragId) {
                        DragAndDropHandler.currentDragged?.removeFromParent();
                    }
                }
            }
        })
        this.addEventListener('dragover', e => {
            if (e.dataTransfer.types.includes('text/cms-value-params'))
                e.preventDefault()
        })
    }

    dragstartHandler(e) {
        let dragId = ++DragAndDropHandler.dragCounter;
        e.dataTransfer.setData('text/cms-value-params', JSON.stringify({
            param: this.param,
            paramConfig: this.collectParameters(),
            dragId
        }))
        DragAndDropHandler.currentDragged = this;
        e.stopPropagation();
    }

    tryAcceptChild(data) {
        return false;
    }
}
customElements.define('abstract-value-edit', AbstractValueEdit);
