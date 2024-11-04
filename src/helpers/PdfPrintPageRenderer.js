import { rgb } from "pdf-lib";

export default class PdfPrintPageRenderer {
    renderText(printPage, pdfPage, font) {
        const { width, height } = pdfPage.getSize();

        for (let i = 0; i < printPage.textFragments.length; i++) {
            const textFragment = printPage.textFragments[i];
            pdfPage.drawText(textFragment.text, {
                x: textFragment.positionLeft,
                y: height - textFragment.positionTop,
                size: (textFragment.fontSizePt * 1.34),       //px
                font: font,
                color: rgb(0, 0, 0),
            });
        }
    }

    async renderImages(printPage, pdfPage) {
        const { width, height } = pdfPage.getSize();

        for (let i = 0; i < printPage.imageFragments.length; i++) {
            const fragment = printPage.imageFragments[i];
            const imageBytes = await fetch(fragment.url).then((res) => res.arrayBuffer());

            const mime = this.getMimeType(imageBytes);
            let image = null;

            if (mime === 'image/png') {
                image = await pdfPage.doc.embedPng(imageBytes)
            } else if (mime === 'image/jpeg') {
                image = await pdfPage.doc.embedJpg(imageBytes)
            }

            if (image != null) {
                const scaled = image.scaleToFit(fragment.width, fragment.height)

                pdfPage.drawImage(image, {
                    x: fragment.x,
                    y: height - fragment.y - fragment.height,
                    width: scaled.width,
                    height: scaled.height,
                });
            }
        }
    }

    getMimeType(imageBytes) {
        const imageMimes = [
            {
                mime: 'image/png',
                pattern: [0x89, 0x50, 0x4e, 0x47]
            },
            {
                mime: 'image/jpeg',
                pattern: [0xff, 0xd8, 0xff]
            }
        ];

        let header = (new Uint8Array(imageBytes)).subarray(0, 4);

        const valid = imageMimes.filter(mime => {
            return mime.pattern.every((p, i) => !p || header[i] === p);
        });

        if (valid.length > 0) {
            return valid[0].mime;
        }

        return null;
    }
};