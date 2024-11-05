import { PDFDocument, StandardFonts, rgb } from "pdf-lib";
import fontKit from '@pdf-lib/fontkit';
import PdfPrintPageBuilder from "./PdfPrintPageBuilder";
import PdfPrintPageRenderer from "./PdfPrintPageRenderer";
import PageLayout from "./PageLayout";
import {
    fetchChapter,
    fetchChapterImages,
    imageUrl
} from "./../api/api.js";
import { PAGE_LAYOUTS } from "../constants/pageLayouts";
import _ from "lodash";

export default class PdfBuilder {

    constructor() {
        this.pdfDocument = null;
        this.pdfFont = null;
        this.pdfBuilder = null;
        this.pdfRenderer = null;
        this.pageNum = 1;
    }

    async build(userCredentials, chapters) {
        const sortedChapters = _.cloneDeep(chapters).sort((a, b) => {
            if (a.createdAt < b.createdAt) {
                return -1;
            }
            if (a.createdAt > b.createdAt) {
                return 1;
            }
            return 0;
        });

        this.pageNum = 1;
        this.pdfDocument = await PDFDocument.create();
        this.pdfFont = await this.loadFont('/assets/fonts/calibri.ttf');

        this.pdfBuilder = new PdfPrintPageBuilder(this.pdfDocument, this.pdfFont);
        this.pdfRenderer = new PdfPrintPageRenderer();

        const defaultLayout = PageLayout.createDefault();

        for (let i = 0; i < sortedChapters.length; i++) {
            const chapterData = await fetchChapter(userCredentials, sortedChapters[i].id);
            const imageUrls = await this.loadImages(userCredentials, sortedChapters[i].id);

            this.pdfBuilder.setImages(imageUrls);
            this.pdfBuilder.setText(chapterData.story);
            this.pdfBuilder.setHeader(
                chapterData.title ?? "",
                chapterData.period ?? "",
                chapterData.location ?? ""
            );

            let chapterPageNum = 0;
            let layoutName = chapterData.layouts[chapterPageNum] ?? "default";

            //First page
            this.pdfBuilder.enableHeader(true);
            await this.createChapterFirstPage(PAGE_LAYOUTS[layoutName]?.layout ?? defaultLayout);

            while (this.pdfBuilder.hasTextRemaining()) {
                chapterPageNum++;
                layoutName = chapterData.layouts[chapterPageNum] ?? "default";
                this.pdfBuilder.enableHeader(false);
                await this.createPage(PAGE_LAYOUTS[layoutName]?.layout ?? defaultLayout);
            }
        }

        return this.pdfDocument;
    }

    async download(pdfDocument, fileName) {
        const pdfBytes = await pdfDocument.save();
        var blob = new Blob([pdfBytes], { type: "application/pdf" });
        var link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        link.download = fileName + ".pdf";
        link.click();
    }

    async createChapterFirstPage(layout) {
        const pdfPage = await this.createPage(layout);

        return pdfPage;
    }

    async createPage(layout) {
        this.pdfBuilder.setPageNumber(this.pageNum);
        this.pdfBuilder.buildPage(layout);
        const pdfPage = this.pdfDocument.addPage();

        this.pdfRenderer.renderText(this.pdfBuilder.page, pdfPage, this.pdfFont);
        await this.pdfRenderer.renderImages(this.pdfBuilder.page, pdfPage);

        this.pageNum++;

        return pdfPage;
    }

    async loadFont(fontUrl) {
        const fontBytes = await fetch(fontUrl).then(res => res.arrayBuffer())
        this.pdfDocument.registerFontkit(fontKit);
        return await this.pdfDocument.embedFont(fontBytes);
    }

    async loadImages(userCredentials, chapterId) {
        const imagesData = await fetchChapterImages(
            userCredentials,
            chapterId
        );

        return imagesData.map((image) => imageUrl(userCredentials, image.id));
    }
};