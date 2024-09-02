import AbstractValueEdit from "./AbstractValueEdit";
import FileUploader from "../../../Js/FileUploader";

export default class ImagesArrayValueEdit extends AbstractValueEdit {
    constructor(paramConfig, param) {
        super(paramConfig, param);
        console.log(paramConfig, param)
    }

    draw() {
        super.draw();
        this.valueInput = new FileUploader(this.drawExtraInfo.bind(this));
        this.valueInput.value = this.paramConfig?.value ?? [];
        this.append(this.valueInput)
    }

    collectParameters() {
        return {source: 'const', value: this.valueInput.value};
    }

    drawExtraInfo(file) {
        return [{
            tagName: 'div',
            children: [
                {text: 'Rozmiar: ' + file.image_width+'px x '+file.image_height+'px'},]
        }];
    }
}
customElements.define('images-array-value-edit', ImagesArrayValueEdit);
