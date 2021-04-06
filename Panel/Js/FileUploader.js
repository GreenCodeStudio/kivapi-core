import {AjaxPanel} from "./ajaxPanel";
import ActiveElementList from "../../Js/ActiveElementList";

export default class FileUploader extends HTMLElement {
    constructor() {
        super();
        this.maxFiles = null;
        this.files = [];
        this.activeElementList = new ActiveElementList(this.addChild('div'), this.files);
        this.activeElementList.item = (x, old) => {
            if (old) return old;
            else if (x.status === 'pending')
                return document.create('div', {text: 'pending'})
            else
                return document.create('div', {
                    children: [{text: x.name}, {
                        tagName: 'button',
                        type: 'button',
                        text: 'usuń',
                        onclick: () => {
                            let index = this.files.indexOf(x);
                            if (index >= 0)
                                this.files.splice(index, 1);
                            this.activeElementList.draw();
                        }
                    }]
                })
        };
        this.addButton = this.addChild('button', {text: 'Dodaj', type: 'button'});
        this.addButton.onclick = () => this.openFileDialog();
    }

    openFileDialog() {
        const input = document.create('input', {type: 'file'});
        input.onchange = (e) => {
            Array.from(input.files).map(f => this.uploadFile(f));
        }
        input.click();
    }

    async uploadFile(file) {
        let placeholder = {'status': 'pending'}
        this.files.push(placeholder);
        this.refreshAddButtonVisibility();
        this.activeElementList.draw();
        let data = await AjaxPanel.File.upload(file, {});
        this.files.splice(this.files.indexOf(placeholder), 1, data);
        this.activeElementList.draw();
    }

    get value() {
        return this.files;
    }

    set value(value) {
        this.files = value;
        this.activeElementList.list = value;
        this.activeElementList.draw();
    }

    refreshAddButtonVisibility() {
        this.addButton.style.display = (this.maxFiles === null | this.files.length < this.maxFiles) ? 'block' : 'none;'
    }
}
customElements.define('file-uploader', FileUploader);