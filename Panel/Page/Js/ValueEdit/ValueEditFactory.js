import ComponentValueEdit from "./ComponentValueEdit";
import SimpleValueEdit from "./SimpleValueEdit";
import StructValueEdit from "./StructValueEdit";
import ArrayValueEdit from "./ArrayValueEdit";
import FileValueEdit from "./FileValueEdit";
import ImageValueEdit from "./ImageValueEdit";
import ContentValueEdit from "./ContentValueEdit";
import ImagesArrayValueEdit from "./ImagesArrayValueEdit";
import EnumValueEdit from "./EnumValueEdit";

export const editParams = {availableComponents: []};

export function generateParam(param, paramConfig) {
    if (param.type == 'struct') {
        let node = new StructValueEdit(paramConfig, param)
        node.draw();
        node.classList.add('subgroup')
        let collectParameters = node.collectParameters.bind(node);
        return {param, node, collectParameters}
    } else if (param.type == 'array' || param.type == 'tree') {
        let node = new ArrayValueEdit(paramConfig, param)
        node.draw();
        node.classList.add('subgroup')
        let collectParameters = node.collectParameters.bind(node);
        return {node, collectParameters};
    } else if (param.type == 'component') {
        let node = new ComponentValueEdit(paramConfig, param, editParams.availableComponents);
        node.draw();
        return {node, collectParameters: node.collectParameters.bind(node)};
    } else if (param.type == 'file') {
        let node = new FileValueEdit(paramConfig, param);
        node.draw();
        return {node, collectParameters: node.collectParameters.bind(node)};
    } else if (param.type == 'image') {
        let node = new ImageValueEdit(paramConfig, param);
        node.draw();
        return {node, collectParameters: node.collectParameters.bind(node)};
    } else if (param.type == 'imagesArray') {
        let node = new ImagesArrayValueEdit(paramConfig, param);
        node.draw();
        return {node, collectParameters: node.collectParameters.bind(node)};
    } else if (param.type == 'content') {
        let node = new ContentValueEdit(paramConfig, param);
        node.draw();
        return {node, collectParameters: node.collectParameters.bind(node)};
    } else if (param.type == 'enum') {
        let node = new EnumValueEdit(paramConfig, param);
        node.draw();
        return {node, collectParameters: node.collectParameters.bind(node)};
    }  else if (param.type == 'string' || param.type == 'int'|| param.type == 'float' || param.type == 'url' || param.type == 'boolean') {
        let node = new SimpleValueEdit(paramConfig, param);
        node.draw();
        return {node, collectParameters: node.collectParameters.bind(node)};
    } else {
        console.error('Unknown type', param.type);
        let node = document.create('div', {text: 'Unknown type: ' + param.type});
        return {
            node, collectParameters: () => {
                return {value: null}
            }
        };
    }
}
