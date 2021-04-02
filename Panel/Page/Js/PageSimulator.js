export default class PageSimulator {
    constructor(wrapper) {
        this.wrapper = wrapper;
        this.width = 320;
        this.height = 480;
        this.topMargin = 0;
        wrapper.querySelectorAll('.pageSimulator-changeResolution').forEach(b => {
            b.onclick = () => {
                this.width = +b.dataset.width;
                this.height = +b.dataset.height;
                this.topMargin = +b.dataset.topMargin;
                this.setSize();
            }
        })
    }

    setSize() {
        const iframe = this.wrapper.querySelector('.pageSimulator-iframe')
        iframe.style.marginRight = -this.width + 'px'
        iframe.style.width = this.width + 'px'
        iframe.style.height = (this.height - this.topMargin) + 'px';
        let scale = iframe.parentNode.clientWidth / this.width;
        iframe.style.transform = `scale(${scale})`;
        iframe.parentNode.style.height = ((this.height - this.topMargin) * scale) + 'px'
    }

    setData(data) {
        const form = document.create('form', {target: 'pageSimulator', action: 'pageSimulator', method: 'post'});
        form.style.display = 'none';
        form.addChild('input', {name: 'data', value: JSON.stringify(data)});
        document.body.appendChild(form);
        form.submit();
        form.remove();
    }
}