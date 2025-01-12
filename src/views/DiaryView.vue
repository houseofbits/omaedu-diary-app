<script setup>
import {
  onMounted,
  reactive,
  ref,
  inject,
  computed,
  nextTick,
  watch,
} from "vue";
import EditChapter from "./ChapterEditView.vue";
import Chapters from "./ChaptersLIstView.vue";
import PrintPreview from "./DiaryPrintView.vue";
import ChaptersHeader from "../components/ChaptersHeader.vue";
import {
  fetchDiary,
  fetchAllChapters,
  fetchChapter,
  postChapter,
  putChapter,
  deleteChapter,
} from "../api/api.js";
import PdfBuilder from "../helpers/PdfBuilder.js";
import { useTheme } from "vuetify";
import useErrorStack from "../composables/ErrorStack.js";
import useSettings from "../composables/Settings.js";
import _ from "lodash";
import useDiarySettings from "../composables/DiarySettings.js";

const { diarySettings, setDiarySettings } = useDiarySettings();

const props = defineProps({
  diaryId: Number,
});

const emit = defineEmits(["close"]);

const {
  settings,
  setSettings,
  getPageBackgroundImageUrl,
  getDiaryBackgroundImageUrl,
} = useSettings();
const { errorMessages, addErrorMessage } = useErrorStack();
const theme = useTheme();
const secondaryColor = theme.current.value.colors.secondary;
const userCredentials = inject("userCredentials");
let user = ref(null);
const chapters = reactive([]);
const selectedChapter = ref(null);
const pdfBuilder = new PdfBuilder();

const isPdfGenerating = ref(false);
const isPrintPreviewEnabled = ref(false);
const errorMessage = ref(
  "Failed to save the chapter. Try to refresh the page and if the problem persists please contact the technical support."
);
const isLoading = ref(true);

async function selectChapter(index) {
  let chapterData = await fetchChapter(userCredentials, chapters[index].id);

  chapters[index].title = chapterData.title;
  chapters[index].story = chapterData.story;
  chapters[index].location = chapterData.location;
  chapters[index].period = chapterData.period;
  chapters[index].layouts = chapterData.layouts;

  selectedChapter.value = index;
}

async function addChapterAndEdit() {
  const data = {
    id: null,
    title: "Chapter title",
    period: new Date().toISOString().slice(0, 10),
    location: "",
    story: "",
    layouts: [],
    diaryId: props.diaryId,
    createdAt: new Date().toISOString(),
  };

  try {
    const chapterData = await postChapter(userCredentials, data);
    data.id = chapterData.id;

    chapters.unshift(data);
    selectedChapter.value = 0;
  } catch (error) {
    addErrorMessage(
      "Failed to add new chapter. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }
}

async function updateChapter(chapter) {
  chapters[selectedChapter.value] = chapter;

  try {
    await putChapter(userCredentials, chapter);
  } catch (error) {
    addErrorMessage(
      "Failed to update chapter. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }
}

async function onDeleteChapter(chapterId) {
  try {
    await deleteChapter(userCredentials, chapterId);
    selectedChapter.value = null;
    const index = chapters.findIndex((chapter) => chapter.id === chapterId);
    if (index >= 0) {
      chapters.splice(index, 1);
    }
  } catch (error) {
    addErrorMessage(
      "Failed to remove chapter. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }
}

onMounted(async () => {
  try {
    const data = await fetchDiary(userCredentials, props.diaryId);
    setDiarySettings(data);
  } catch (error) {
    addErrorMessage(
      "Failed to load diary. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }

  try {
    let chaptersData = await fetchAllChapters(userCredentials, props.diaryId);
    chaptersData = chaptersData.map((chapter) => {
      return {
        id: chapter.id,
        title: chapter.title,
        period: chapter.period,
        location: "",
        story: "",
        layouts: [],
        diaryId: props.diaryId,
        createdAt: chapter.createdAt,
      };
    });

    chapters.length = 0;
    chapters.push(...chaptersData);
  } catch (error) {
    addErrorMessage(
      "Failed to load list of chapters. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }

  isLoading.value = false;
});

async function createPdf() {
  isPdfGenerating.value = true;

  const pdfDocument = await pdfBuilder.build(
    userCredentials,
    chapters,
    settings.isPageNumberingEnabled,
    settings.isTextJustifyEnabled,
    getDiaryBackgroundImageUrl()
  );
  // await pdfBuilder.download(pdfDocument, user.value.diaryTitle);

  isPdfGenerating.value = false;
}

const datePeriod = computed(() => {
  return ""; //user.value.datePeriodFrom + " - " + user.value.datePeriodTo;
});

function print() {
  isPrintPreviewEnabled.value = true;
}
</script>

<template>
  <template v-if="!isLoading">
    <template v-if="!isPrintPreviewEnabled">
      <edit-chapter
        v-if="selectedChapter !== null"
        :chapter="chapters[selectedChapter]"
        @close="selectedChapter = null"
        @update="updateChapter"
        @delete="onDeleteChapter"
      ></edit-chapter>
      <template v-else>
        <chapters-header
          :diary-id="diaryId"
          :date-period="datePeriod"
          :is-chapters-empty="chapters.length == 0"
          :is-pdf-generating="isPdfGenerating"
          :can-delete="chapters.length == 0"
          @add-chapter="addChapterAndEdit"
          @download="createPdf"
          @print="print"
          @close="emit('close')"
        />
        <chapters :chapters="chapters" @select="selectChapter" />
      </template>
    </template>

    <print-preview
      v-else
      :chapters="chapters"
      @close="isPrintPreviewEnabled = false"
    ></print-preview>
  </template>
</template>

<style>
.header-bg {
  /* background-image: var(--header-bg-image); */
  background-image: url(/assets/images/balti-ziedi.jpg);
  background-size: cover !important;
  /* background: linear-gradient(0deg, rgba(94, 90, 85, 0.2) 0%, rgb(201, 192, 181) 100%); */
}
</style>