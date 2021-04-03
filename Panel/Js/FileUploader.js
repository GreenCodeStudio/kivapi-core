import {AjaxPanel} from "./ajaxPanel";
import ActiveElementList from "../../../Js/ActiveElementList";

export default class FileUploader extends HTMLElement {
    constructor() {
        super();
        this.append('File uploader');
        this.addEventListener('click', () => this.openFileDialog())
        this.files = [];
        this.activeElementList = new ActiveElementList(this, this.files);
        this.activeElementList.item = (x, old) => old ?? document.create('div', {text: JSON.stringify(x)});
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
}
customElements.define('file-uploader', FileUploader);