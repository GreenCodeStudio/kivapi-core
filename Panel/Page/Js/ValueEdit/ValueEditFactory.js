import ComponentValueEdit from "./ComponentValueEdit";
import SampleValueEdit from "./SampleValueEdit";
import StructValueEdit from "./StructValueEdit";
import ArrayValueEdit from "./ArrayValueEdit";
import FileValueEdit from "./FileValueEdit";
import ImageValueEdit from "./ImageValueEdit";
import ContentValueEdit from "./ContentValueEdit";

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
    } else if (param.type == 'content') {
        let node = new ContentValueEdit(paramConfig, param);
        node.draw();
        return {node, collectParameters: node.collectParameters.bind(node)};
    } else {
        let node = new SampleValueEdit(paramConfig, param);
        node.draw();
        return {node, collectParameters: node.collectParameters.bind(node)};
    }
}
