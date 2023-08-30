module.exports = function loader(xml) {
    //var callback = this.async();
    var DOMParser = require('xmldom').DOMParser;
    var doc = new DOMParser().parseFromString(xml);
    return `
import {I18nNode} from "../../Core/Js/Internationalization/i18nNode.js";
import {I18nTextValue} from "../../Core/Js/Internationalization/i18nTextValue.js";
import {Translator} from "../../Core/Js/Internationalization/translator.js";
import {LanguagesHierarchy} from "../../Core/Js/Internationalization/languagesHierarchy.js";
export const node = ${xmlToNode(doc.documentElement)};
export const translator=new Translator(LanguagesHierarchy.default, node.node);
export function t(q){var x=translator.translate(q);return x?x.toString():'[['+q+']]';}
`;
}

function xmlToNode(xml) {
    let childNodes = Array.from(xml.childNodes).filter(x => x.tagName == 'node').map(x => xmlToNode(x));
    let values = Array.from(xml.childNodes).filter(x => x.tagName == 'value').map(x => xmlToValue(x));
    var name = xml.getAttribute("name")
    return `{name:${JSON.stringify(name)}, node:new I18nNode([${values.join(',')}],[${childNodes.join(',')}])}`;
}

function xmlToValue(xml) {
    var lang = xml.getAttribute("lang")
    var value = xml.textContent;
    return `{lang:${JSON.stringify(lang)}, value: new I18nTextValue(${JSON.stringify(value)})}`;
}