import {AjaxPanel} from "./ajaxPanel";
import ActiveElementList from "../../Js/ActiveElementList";

export default class FileUploader extends HTMLElement {
    constructor(drawExtraInfo=null) {
        super();
        this.maxFiles = null;
        this.files = [];
        this.drawExtraInfo = drawExtraInfo;
        this.activeElementList = new ActiveElementList(this.addChild('div'), this.files);
        this.activeElementList.item = (x, old) => {
            console.log(x);
            if (old) return old;
            else if (x.status === 'pending') {
                return document.create('div.pendingFile', {text: 'pending'})
            }
            else {
                const extraInfo = this.drawExtraInfo ? this.drawExtraInfo(x) : [];
                return document.create('div.uploadedFile', {
                    draggable: "true",
                    children: [
                        {text: x.name || 'Bez nazwy'},
                        {text: 'Typ: ' + x.mime},
                        {text: 'Rozmiar: ' + this.bytesToHumanReadable(x.size)},
                        ...extraInfo,
                        {
                        tagName: 'div',
                        className: 'button',
                        text: 'usuń',
                        onclick: () => {
                            let index = this.files.indexOf(x);
                            if (index >= 0)
                                this.files.splice(index, 1);
                            this.activeElementList.draw();
                            this.dispatchEvent(new Event('change', {"bubbles": true, "cancelable": false}));
                            this.refreshAddButtonVisibility();
                        }
                    }],
                    ondragstart: (e) => {
                        e.dataTransfer.setData('text/cms-file', JSON.stringify(x));
                    }
                })
            }
        };
        this.addEventListener('dragover', (e) => {
            e.preventDefault();
        });
        this.addEventListener('drop', (e) => {
            e.preventDefault();
            let data = e.dataTransfer.getData('text/cms-file');
            if(data){
                let file = JSON.parse(data);
                let index= this.files.indexOf(this.files.find(x=>x.id === file.id));
                if(index >= 0 && !e.ctrlKey){
                    this.files.splice(index, 1);
                }
                if(this.files.length+1 <= this.maxFiles || this.maxFiles === null) {
                    let tmp=[...this.activeElementList.wrapper.children].map(x=>x.getBoundingClientRect().top);
                    let topDiff=[...this.activeElementList.wrapper.children].map(x=>x.getBoundingClientRect().top-e.layerY).map(Math.abs);
                    let index = topDiff.indexOf(Math.min(...topDiff));
                    if(index >= 0)
                        this.files.splice(index, 0, file);
                    else
                    this.files.push(file);
                }
                this.activeElementList.draw();
                this.dispatchEvent(new Event('change', {"bubbles": true, "cancelable": false}));
            }
        });
        this.addButton = this.addChild('.button', {text: 'Dodaj'});
        this.addButton.onclick = () => this.openFileDialog();
        this.refreshAddButtonVisibility();
    }

    openFileDialog() {
        const input = document.create('input', {type: 'file'});
        if(this.maxFiles != 1){
            input.setAttribute('multiple', 'multiple');
        }
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
        this.dispatchEvent(new Event('change', {"bubbles": true, "cancelable": false}));
    }

    get value() {
        return this.files;
    }

    set value(value) {
        this.files = value;
        this.activeElementList.list = value;
        this.activeElementList.draw();
        this.refreshAddButtonVisibility();
    }

    refreshAddButtonVisibility() {
        this.addButton.classList.toggle('isHidden', (this.maxFiles !== null && this.files.length >= this.maxFiles))
    }

    bytesToHumanReadable(bytes) {
        if (bytes > 1024 ** 3)
            return this.numberHumanRund(bytes / 1024 ** 3) + 'GB';
        else if (bytes > 1024 ** 2)
            return this.numberHumanRund(bytes / 1024 ** 2) + 'MB';
        else if (bytes > 1024)
            return this.numberHumanRund(bytes / 1024) + 'kB';
        else
            return bytes + 'B';
    }

    numberHumanRund(num) {
        if (num > 100) return Math.round(num).toString();
        else if (num > 10) return num.toFixed(1);
        else return num.toFixed(2);
    }
}
customElements.define('file-uploader', FileUploader);
