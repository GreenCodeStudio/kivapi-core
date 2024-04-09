console.log('InSIteEdit index.js loaded')

document.addEventListener('DOMContentLoaded', function () {

    traverseDom(document.body)

});

function traverseDom(node) {
    if (node.tagName == 'script' || node.tagName == 'style') {
        return
    }
    if (node.nodeType == document.TEXT_NODE) {
        console.log(node.nodeValue)
        for(let field of window.inSiteEditData){
            if(node.nodeValue.includes(field.random)){
                console.log('found field', field)
                let index = node.nodeValue.indexOf(field.random)
                if(index > 0){
                    let textNode = document.createTextNode(node.nodeValue.substring(0, index))
                    node.parentNode.insertBefore(textNode, node)
                    traverseDom(textNode)
                }
                let span = document.createElement('span')
                span.textContent = field.value
                span.dataset.path=JSON.stringify(field.path)
                span.contentEditable = true
                node.parentNode.insertBefore(span, node)
                node.nodeValue = node.nodeValue.substring(index + field.random.length)
            }
        }
    }
    for (var i = 0; i < node.childNodes.length; i++) {
        traverseDom(node.childNodes[i])
    }
}
