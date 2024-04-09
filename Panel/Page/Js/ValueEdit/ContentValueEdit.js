import AbstractValueEdit from "./AbstractValueEdit";
import {modal} from "../../../Js/modal";

export default class ContentValueEdit extends AbstractValueEdit {
    constructor(paramConfig, param) {
        super(paramConfig, param);
        this.mime = this.paramConfig?.value?.mime || 'text/plain';
    }

    draw() {
        super.draw();
        this.addChild('select.mimeChangeSelect', {
            children: [{
                tagName: 'option',
                value: 'text/plain',
                text: 'Plain text',
                selected: this.mime == 'text/plain'
            }, {
                tagName: 'option', value: 'text/html',
                text: 'HTML WYSIWYG',
                selected: this.mime == 'text/html'
            }],
            onchange: async e => {
                let old = this.mime;
                let next = e.target.value;
                if (old == 'text/plain') {
                    if (next == 'text/html') {
                        let tmpConverter = document.create('div');
                        tmpConverter.textContent = this.textTextarea.value;
                        this.textTextarea.remove();
                        this.htmlContenteditable = this.addChild('div', {
                            contenteditable: "true",
                            html: tmpConverter.innerHTML
                        });
                        this.mime = next;
                    }
                } else if (old == 'text/html') {
                    if (next == 'text/plain') {
                        if (await modal("Convertion to text can remove formatting. Are you shure?", "info", [{
                            text: 'cancel',
                            value: false
                        }, {text: 'ok', value: true}])) {

                            this.htmlContenteditable.remove();
                            this.textTextarea = this.addChild('textarea', {text: this.htmlContenteditable.textContent});
                            this.mime = next;
                        }
                    }
                }
            }
        })
        if (this.mime == 'text/plain')
            this.textTextarea = this.addChild('textarea', {text: this.paramConfig?.value?.text ?? this.param.default ?? ''});
        else if (this.mime == 'text/html')
            this.htmlContenteditable = this.addChild('div', {
                contenteditable: "true",//need to be string, not boolean
                html: this.paramConfig?.value?.html ?? this.param.default ?? ''
            });

    }

    collectParameters() {
        let ret = {source: 'const', value: {mime: this.mime}};
        if (this.mime == 'text/plain')
            ret.value.text = this.textTextarea.value;
        else if (this.mime == 'text/html')
            ret.value.html = this.htmlContenteditable.innerHTML;
        return ret;
    }
}
customElements.define('content-value-edit', ContentValueEdit);