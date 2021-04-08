export default class PageSimulator {
    constructor(wrapper) {
        this.wrapper = wrapper;
        this.width = 480;
        this.height = 320;
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
        iframe.style.marginRight = -this.width + 'px'
        iframe.style.width = this.width + 'px'
        iframe.style.height = (this.height - this.topMargin) + 'px';
        let scale = iframe.parentNode.clientWidth / this.width;
        iframe.style.transform = `scale(${scale})`;
        iframe.parentNode.style.height = ((this.height - this.topMargin) * scale) + 'px'
    }

    setData(data) {
        this.data = data;
        this.open();
    }

    open(newWindow = false) {
        this.data.showParents = this.wrapper.querySelector('.pageSimulator-showParents').checked;
        const form = document.create('form', {
            target: newWindow ? '_blank' : 'pageSimulator',
            action: 'pageSimulator',
            method: 'post'
        });
        form.style.display = 'none';
        form.addChild('input', {name: 'data', value: JSON.stringify(this.data)});
        document.body.appendChild(form);
        form.submit();
        form.remove();
    }
}