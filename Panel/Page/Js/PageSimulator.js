export default class PageSimulator {
    constructor(wrapper) {
        this.wrapper = wrapper;
        this.width = 1366;
        this.height = 786;
        this.topMargin = 0;
        wrapper.querySelectorAll('.pageSimulator-changeResolution').forEach(b => {
            b.onclick = () => {
                this.width = +b.dataset.width;
                this.height = +b.dataset.height;
                this.topMargin = +b.dataset.topMargin;
                this.setSize();
            }
        })
        wrapper.querySelectorAll('.pageSimulator-rotateResolution').forEach(b => {
            b.onclick = () => {
                let tmp = this.width;
                this.width = this.height;
                this.height = tmp;
                this.setSize();
            }
        })
        wrapper.querySelectorAll('.pageSimulator-openFullSize').forEach(b => {
            b.onclick = () => {
                this.open(true);
            }
        })
        wrapper.querySelectorAll('.pageSimulator-showParents').forEach(b => {
            b.onchange = () => {
                this.open(false);
            }
        })
    }

    setSize() {
        const iframe = this.wrapper.querySelector('.pageSimulator-iframe')
        const div = iframe.parentNode;
        const wrapper = div.parentNode;
        let wrapperWidth = (wrapper.clientWidth - 20);
        let scaleW = wrapperWidth / this.width;
        let scaleH = innerHeight / this.height * 0.9;
        let scale = Math.min(scaleW, scaleH);
        iframe.style.transform = `scale(${scale})`;
        iframe.style.setProperty('--scale', scale);
        iframe.style.width = this.width + 'px'
        iframe.style.height = (this.height - this.topMargin) + 'px';
        div.style.width = this.width * scale + 'px'
        div.style.marginRight = -this.width * scale + 'px'
        div.style.marginLeft = (wrapperWidth - this.width * scale) / 2 + 'px';
        div.style.height = ((this.height - this.topMargin) * scale) + 'px'
    }

    setData(data) {
        this.data = data;
        this.open();
    }

    open(newWindow = false) {
        this.data.showParents = this.wrapper.querySelector('.pageSimulator-showParents').checked;
        const form = document.create('form', {
            target: newWindow ? '_blank' : 'pageSimulator',
            action: 'PageSimulator',
            method: 'post'
        });
        form.style.display = 'none';
        form.addChild('input', {name: 'data', value: JSON.stringify(this.data)});
        document.body.appendChild(form);
        if (newWindow || document.querySelector('[name="pageSimulator"]') !== null) {
            form.submit();
        }
        form.remove();
    }
}