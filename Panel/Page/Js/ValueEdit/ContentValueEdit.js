import AbstractValueEdit from "./AbstractValueEdit";

export default class ContentValueEdit extends AbstractValueEdit {
    constructor(paramConfig, param) {
        super(paramConfig, param);
        this.mime = this.paramConfig?.value?.mime || 'text/plain';
    }

    draw() {
        super.draw();
        if (this.mime == 'text/plain')
            this.textTextarea = this.addChild('textarea', {text: this.paramConfig?.value?.text ?? this.param.default ?? ''});
        else if (this.mime == 'text/html')
            this.htmlTextarea = this.addChild('textarea', {text: this.paramConfig?.value?.html ?? this.param.default ?? ''});//tmp

    }

    collectParameters() {
        let ret = {source: 'const', value: {mime: this.mime}};
        if (this.mime == 'text/plain')
            ret.value.text = this.textTextarea.value;
        else if (this.mime == 'text/html')
            ret.value.html = this.htmlTextarea.value;
        return ret;
    }
}
customElements.define('content-value-edit', ContentValueEdit);