

export default class CanvasPrintPageRenderer {
    renderText(ctx, page) {
        ctx.font = "12pt Calibri";
        ctx.fillStyle = "black";
        for (let i = 0; i < page.textFragments.length; i++) {
            const textFragment = page.textFragments[i];
            ctx.font = textFragment.fontSizePt + "pt Calibri";
            ctx.fillText(
                textFragment.text,
                textFragment.positionLeft,
                textFragment.positionTop
            );
        }
    }

    async renderImages(ctx, page) {

        for (let i = 0; i < page.imageFragments.length; i++) {
            const fragment = page.imageFragments[i];

            let img = new Image();
            await new Promise(r => img.onload = r, img.src = fragment.url);

            const imgAspect = img.height / img.width;
            const destAspect = fragment.getAspect();

            let imgWidth = img.width;
            let imgHeight = img.height;
            let imgX = 0;
            let imgY = 0;
            if (imgAspect > destAspect) {
                imgHeight = imgWidth * destAspect;
                imgY = (img.height - imgHeight) * 0.5;
            } else {
                imgWidth = imgHeight / destAspect;
                imgX = (img.width - imgWidth) * 0.5;
            }

            ctx.drawImage(img, imgX, imgY, imgWidth, imgHeight, fragment.x, fragment.y, fragment.width, fragment.height);
        }
    }

    renderLayout(ctx, layout) {
        layout.drawDebugBorders(ctx);
    }
};