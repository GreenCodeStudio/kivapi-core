import AbstractValueEdit from "./AbstractValueEdit";
import FileUploader from "../../../Js/FileUploader";

export default class ImageValueEdit extends AbstractValueEdit {
    constructor(paramConfig, param) {
        super(paramConfig, param);
    }

    draw() {
        super.draw();
        this.valueInput = new FileUploader();
        this.valueInput.maxFiles = 1;
        this.valueInput.value = this.paramConfig?.value ? [this.paramConfig.value] : [];
        this.append(this.valueInput)
    }

    collectParameters() {
        return {source: 'const', value: this.valueInput.value[0] ?? null};
    }
}
customElements.define('image-value-edit', ImageValueEdit);