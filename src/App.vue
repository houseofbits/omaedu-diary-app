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
import EditChapter from "./components/EditChapter.vue";
import Chapters from "./components/Chapters.vue";
import PrintPreview from "./components/PrintPreview.vue";
import ChaptersHeader from "./components/ChaptersHeader.vue";
import {
  fetchUser,
  updateUser,
  fetchAllChapters,
  fetchChapter,
  postChapter,
  putChapter,
  deleteChapter,
} from "./api/api.js";
import PdfBuilder from "./helpers/PdfBuilder";
import { useTheme } from "vuetify";
import useErrorStack from "./composables/ErrorStack";
import useSettings from "./composables/Settings";
import _ from "lodash";

const { settings, setSettings, getPageBackgroundImageUrl, getDiaryBackgroundImageUrl } = useSettings();
const { errorMessages, addErrorMessage } = useErrorStack();
const theme = useTheme();
const secondaryColor = theme.current.value.colors.secondary;
const userCredentials = inject("userCredentials");
let user = ref(null);
const chapters = reactive([]);
const selectedChapter = ref(null);
const pdfBuilder = new PdfBuilder();
const isErrorVisible = ref(true);
const isPdfGenerating = ref(false);
const isPrintPreviewEnabled = ref(false);
const errorMessage = ref(
  "Failed to save the chapter. Try to refresh the page and if the problem persists please contact the technical support."
);

async function selectChapter(index) {
  let chapterData = await fetchChapter(userCredentials, chapters[index].id);

  chapters[index].title = chapterData.title;
  chapters[index].story = chapterData.story;
  chapters[index].location = chapterData.location;
  chapters[index].period = chapterData.period;
  chapters[index].layouts = chapterData.layouts;

  selectedChapter.value = index;
}

async function getUserData() {
  try {
    const userData = await fetchUser(userCredentials);
    user.value = userData;
    setSettings(user.value.settings);
  } catch (error) {
    addErrorMessage(
      "Failed to load user data. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }
}

async function addChapterAndEdit() {
  const data = {
    id: null,
    title: "Chapter title",
    period: new Date().toISOString().slice(0, 10),
    location: "",
    story: "",
    layouts: [],
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

  await getUserData();
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

async function updateTitle(title) {
  user.value.diaryTitle = title;

  try {
    await updateUser(userCredentials, user.value);
  } catch (error) {
    addErrorMessage(
      "Failed to update title. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }
}

watch(
  () => settings,
  async () => {
    user.value.settings = _.cloneDeep(settings);

    try {
      await updateUser(userCredentials, user.value);
    } catch (error) {
      addErrorMessage(
        "Failed to update title. Try to refresh the page and if the problem persists please contact the technical support."
      );
    }
  },
  { deep: true }
);

onMounted(async () => {
  try {
    let chaptersData = await fetchAllChapters(userCredentials);
    chaptersData = chaptersData.map((chapter) => {
      return {
        id: chapter.id,
        title: chapter.title,
        period: chapter.period,
        location: "",
        story: "",
        layouts: [],
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

  await getUserData();
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
  await pdfBuilder.download(pdfDocument, user.value.diaryTitle);

  isPdfGenerating.value = false;
}

const cssThemeProps = computed(() => {
  return {
    "--header-bg-image": "url(" + getPageBackgroundImageUrl() + ")",
  };
});

const datePeriod = computed(() => {
  return user.value.datePeriodFrom + " - " + user.value.datePeriodTo;
});

function calculateSnackbarMargin(i) {
  return i * 60 + "px";
}

function print() {
  isPrintPreviewEnabled.value = true;
}
</script>

<template>
  <v-app v-if="user" :style="cssThemeProps">
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
          :title="user.diaryTitle"
          :date-period="datePeriod"
          :is-chapters-empty="chapters.length == 0"
          :is-pdf-generating="isPdfGenerating"
          @add-chapter="addChapterAndEdit"
          @update-title="updateTitle"
          @download="createPdf"
          @print="print"
        />
        <chapters :chapters="chapters" @select="selectChapter" />
      </template>
    </template>

    <print-preview
      v-else
      :chapters="chapters"
      @close="isPrintPreviewEnabled = false"
    ></print-preview>
  </v-app>

  <v-snackbar
    v-for="(message, i) in errorMessages"
    :style="{ 'margin-bottom': calculateSnackbarMargin(i) }"
    :key="i"
    v-model="isErrorVisible"
    :timeout="-1"
    color="red-darken-3"
  >
    {{ message.text }}
  </v-snackbar>
</template>

<style>
.header-bg {
  /* background-image: var(--header-bg-image); */
  background-image: url(/assets/images/balti-ziedi.jpg);
  background-size: cover !important;
  /* background: linear-gradient(0deg, rgba(94, 90, 85, 0.2) 0%, rgb(201, 192, 181) 100%); */
}
</style>