

export default class CanvasPrintPageRenderer {

    constructor(scale) {
        this.scale = scale ?? 1.0;
        this.fontFace = "Corbel";
    }

    async renderText(ctx, page) {
        ctx.fillStyle = "black";
        for (let i = 0; i < page.textFragments.length; i++) {
            const textFragment = page.textFragments[i];
            const fontSize = textFragment.fontSizePt * this.scale;
            ctx.font = fontSize + "pt " + this.fontFace;
            ctx.fillText(
                textFragment.text,
                textFragment.positionLeft * this.scale,
                textFragment.positionTop * this.scale
            );
        }
    }

    async renderBackground(ctx, imageUrl) {
        if (imageUrl === null) {
            return;
        }

        let img = new Image();
        await new Promise(r => img.onload = r, img.src = imageUrl);

        ctx.drawImage(img, 0, 0, img.width, img.height, 0, 0,
            595 * this.scale,
            842 * this.scale
        );
    }

    async renderImages(ctx, page) {

        for (let i = 0; i < page.imageFragments.length; i++) {
            const fragment = page.imageFragments[i];

            let img = new Image();
            await new Promise(r => img.onload = r, img.src = fragment.url);

            const imgAspect = img.height / img.width;
            const destAspect = fragment.getAspect();

            // let imgWidth = img.width;
            // let imgHeight = img.height;
            // let imgX = 0;
            // let imgY = 0;
            // if (imgAspect > destAspect) {
            //     imgHeight = imgWidth * destAspect;
            //     imgY = (img.height - imgHeight) * 0.5;
            // } else {
            //     imgWidth = imgHeight / destAspect;
            //     imgX = (img.width - imgWidth) * 0.5;
            // }

            // ctx.drawImage(img, imgX, imgY, imgWidth, imgHeight, fragment.x, fragment.y, fragment.width, fragment.height);

            let targetWidth = img.width;
            let targetHeight = img.height;
            let imgX = 0;
            let imgY = 0;
            if (imgAspect > destAspect) {
                targetWidth = fragment.height / imgAspect;
                targetHeight = fragment.height;
            } else {
                targetWidth = fragment.width;
                targetHeight = fragment.width * imgAspect;
            }

            ctx.drawImage(img, imgX, imgY, img.width, img.height, fragment.x * this.scale, fragment.y * this.scale,
                targetWidth * this.scale,
                targetHeight * this.scale
            );
        }
    }

    renderLayout(ctx, layout) {

        for (let i = 0; i < layout.imageRegions.length; i++) {

            ctx.fillStyle = "rgba(0,0,1,0.1)";
            ctx.fillRect(layout.imageRegions[i].getInnerX(), layout.imageRegions[i].getInnerY(), layout.imageRegions[i].getInnerWidth(), layout.imageRegions[i].getInnerHeight());
        }

        // layout.drawDebugBorders(ctx);
    }
};