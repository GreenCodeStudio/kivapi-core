import AbstractValueEdit from "./AbstractValueEdit";
import FileUploader from "../../../Js/FileUploader";

export default class ImagesArrayValueEdit extends AbstractValueEdit {
    constructor(paramConfig, param) {
        super(paramConfig, param);
    }

    draw() {
        super.draw();
        this.valueInput = new FileUploader();
        this.valueInput.value = this.paramConfig?.value ?? [];
        this.append(this.valueInput)
    }

    collectParameters() {
        return {source: 'const', value: this.valueInput.value};
    }
}
customElements.define('images-array-value-edit', ImagesArrayValueEdit);
