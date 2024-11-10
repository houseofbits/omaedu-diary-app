<script setup>
import { computed, onMounted, ref, reactive, watch, inject } from "vue";
import _ from "lodash";
import { fetchChapter, fetchChapterImages, imageUrl } from "./../api/api.js";
import { PAGE_LAYOUTS } from "../constants/pageLayouts";
import CanvasPrintPageBuilder from "../helpers/CanvasPrintPageBuilder";
import PrintPagePreview from "./PrintPagePreview.vue";
import useSettings from "../composables/Settings";

const { settings } = useSettings();
const emit = defineEmits(["close"]);
const props = defineProps({
  chapters: Array,
});

const userCredentials = inject("userCredentials");

let ctx = null;
const canvasRef = ref(null);
const printPages = reactive([]);

async function loadImages(userCredentials, chapterId) {
  const imagesData = await fetchChapterImages(userCredentials, chapterId);

  return imagesData.map((image) => imageUrl(userCredentials, image.id));
}

async function generatePages() {
  const sortedChapters = _.cloneDeep(props.chapters).sort((a, b) => {
    if (a.createdAt < b.createdAt) {
      return -1;
    }
    if (a.createdAt > b.createdAt) {
      return 1;
    }
    return 0;
  });

  const pageBuilder = new CanvasPrintPageBuilder(ctx);
  pageBuilder.isGeneratePageNumberEnabled = settings.isPageNumberingEnabled;
  pageBuilder.isTextJustifyEnabled = settings.isTextJustifyEnabled;

  let pageNum = 1;
  for (let i = 0; i < sortedChapters.length; i++) {
    const chapterData = await fetchChapter(
      userCredentials,
      sortedChapters[i].id
    );
    const imageUrls = await loadImages(userCredentials, sortedChapters[i].id);

    pageBuilder.setImages(imageUrls);
    pageBuilder.setText(chapterData.story);
    pageBuilder.setHeader(
      chapterData.title ?? "",
      chapterData.period ?? "",
      chapterData.location ?? ""
    );

    let chapterPageNum = 0;
    while (pageBuilder.hasTextRemaining()) {
      let layoutName = chapterData.layouts[chapterPageNum] ?? "default";

      pageBuilder.enableHeader(chapterPageNum === 0);

      pageBuilder.setPageNumber(pageNum);
      pageBuilder.buildPage(PAGE_LAYOUTS[layoutName]?.layout ?? defaultLayout);

      printPages.push(pageBuilder.page);

      chapterPageNum++;
      pageNum++;
    }
  }
}

function print() {
  //   window.addEventListener(
  //     "afterprint",
  //     () => (isPrintPreviewEnabled.value = false)
  //   );

  window.print();
}

onMounted(async () => {
  ctx = canvasRef.value.getContext("2d");
  if (ctx != null) {
    await document.fonts.load("12pt Corbel");
    await generatePages();
  }
});
</script>

<template>
  <canvas
    id="temporary-print-canvas"
    width="595"
    height="842"
    ref="canvasRef"
  ></canvas>

  <v-app-bar :height="50">
    <v-btn variant="tonal" class="ml-3" @click="emit('close')">
      <template v-slot:prepend>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          height="20"
          width="17.5"
          viewBox="0 0 448 512"
        >
          <path
            d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"
          />
        </svg>
      </template>
      Back</v-btn
    >
    <v-btn variant="tonal" class="ml-3" @click="print()">
      <template v-slot:prepend>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          height="20"
          width="25"
          viewBox="0 0 640 512"
        >
          <path
            d="M128 0C92.7 0 64 28.7 64 64l0 96 64 0 0-96 226.7 0L384 93.3l0 66.7 64 0 0-66.7c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0L128 0zM384 352l0 32 0 64-256 0 0-64 0-16 0-16 256 0zm64 32l32 0c17.7 0 32-14.3 32-32l0-96c0-35.3-28.7-64-64-64L64 192c-35.3 0-64 28.7-64 64l0 96c0 17.7 14.3 32 32 32l32 0 0 64c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-64zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"
          />
        </svg>
      </template>
      Print</v-btn
    >
  </v-app-bar>

  <v-container max-width="800" class="print-preview-container">
    <div class="print-page-top-spacer"></div>
    <template v-for="(page, index) in printPages" :key="index">
      <print-page-preview :print-page="page"></print-page-preview>

      <div class="pagebreak"></div>
    </template>
  </v-container>
</template>

<style>
#temporary-print-canvas {
  width: 100%;
  display: none;
}
.print-page-top-spacer {
  height: 50px;
}
</style>