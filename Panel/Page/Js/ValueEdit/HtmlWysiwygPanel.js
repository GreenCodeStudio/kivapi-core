export default class HtmlWysiwygPanel extends HTMLElement{
    constructor() {
        super();

        this.addChild('button', {text: 'Bold', onclick: (e) => {
                e.preventDefault();
                e.stopPropagation();
                document.execCommand('bold')
            }})

        this.addChild('button', {text: 'Undo', onclick: (e) => {
                e.preventDefault();
                e.stopPropagation();
                document.execCommand('undo')
            }})
        this.addChild('button', {text: 'Redo', onclick: (e) => {
                e.preventDefault();
                e.stopPropagation();
                document.execCommand('redo')
            }})
        this.addChild('button', {text: 'Create link', onclick: (e) => {
                e.preventDefault();
                e.stopPropagation();
                let url = prompt("Enter the URL");
                document.execCommand('createLink', false, url)
            }})
        this.addChild('button', {text: 'Unlink', onclick: (e) => {
                e.preventDefault();
                e.stopPropagation();
                document.execCommand('unlink')
            }})
        this.addChild('button', {text: 'InsertOrderedList', onclick: (e) => {
                e.preventDefault();
                e.stopPropagation();
                document.execCommand('insertOrderedList')
            }})
        this.addChild('button', {text: 'InsertUnorderedList', onclick: (e) => {
                e.preventDefault();
                e.stopPropagation();
                document.execCommand('insertUnorderedList')
            }})
        this.addChild('button', {text: 'RemoveFormat', onclick: (e) => {
                e.preventDefault();
                e.stopPropagation();
                document.execCommand('removeFormat')
            }})
    }
}
customElements.define('html-wysiwyg-panel', HtmlWysiwygPanel);
