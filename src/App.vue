<script setup>
import { onMounted, reactive, ref, inject, computed } from "vue";
import EditChapter from "./components/EditChapter.vue";
import Chapters from "./components/Chapters.vue";
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
import convertHEXToRGBA from "./helpers/convertHEXToRGBA";
import { useTheme } from "vuetify";

const theme = useTheme();
const secondaryColor = theme.current.value.colors.secondary;

const userCredentials = inject("userCredentials");
let user = ref(null);
const chapters = reactive([]);
const selectedChapter = ref(null);
const pdfBuilder = new PdfBuilder();

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
  };

  const chapterData = await postChapter(userCredentials, data);
  data.id = chapterData.id;

  chapters.push(data);
  selectedChapter.value = chapters.length - 1;
}

async function updateChapter(chapter) {
  chapters[selectedChapter.value] = chapter;

  await putChapter(userCredentials, chapter);
}

async function onDeleteChapter(chapterId) {
  await deleteChapter(userCredentials, chapterId);
  selectedChapter.value = null;
  const index = chapters.findIndex((chapter) => chapter.id === chapterId);
  if (index >= 0) {
    chapters.splice(index, 1);
  }
}

async function updateTitle(title) {
  user.value.diaryTitle = title;

  await updateUser(userCredentials, user.value);
}

onMounted(async () => {
  let chaptersData = await fetchAllChapters(userCredentials);
  chaptersData = chaptersData.map((chapter) => {
    return {
      id: chapter.id,
      title: chapter.title,
      period: chapter.period,
      location: "",
      story: "",
      layouts: [],
    };
  });

  chapters.length = 0;
  chapters.push(...chaptersData);

  const userData = await fetchUser(userCredentials);
  user.value = userData;

  console.log(secondaryColor);
});

async function createPdf() {
  const pdfDocument = await pdfBuilder.build(userCredentials, chapters);
  await pdfBuilder.download(pdfDocument);
}

const cssThemeProps = computed(() => {
  return {
    '--header-gradient-start': convertHEXToRGBA(secondaryColor, 100),
    '--header-gradient-end': convertHEXToRGBA(secondaryColor, 50),
  };
});
</script>

<template>
  <v-app v-if="user" :style="cssThemeProps">
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
        :is-chapters-empty="chapters.length == 0"
        @add-chapter="addChapterAndEdit"
        @update-title="updateTitle"
        @download="createPdf"
      />
      <chapters :chapters="chapters" @select="selectChapter" />
    </template>
  </v-app>
</template>

<style>
.header-bg {
  background: linear-gradient(0deg, var(--header-gradient-end) 0%, var(--header-gradient-start) 100%);
}
</style>