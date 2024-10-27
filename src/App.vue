<script setup>
import { onMounted, reactive, ref } from "vue";
import EditChapter from "./components/EditChapter.vue";
import Chapters from "./components/Chapters.vue";
import ChaptersHeader from "./components/ChaptersHeader.vue";
import {
  fetchUser,
  fetchAllChapters,
  fetchChapter,
  postChapter,
  putChapter,
} from "./api/api.js";

const user = reactive(null);
const chapters = reactive([]);
const selectedChapter = ref(null);

function fetchApi() {
  // fetch('api.php', {
  //   method: "GET",
  //   body: JSON.stringify({ username: "example" }),
  //   headers: {
  //   'Content-Type': 'application/json'
  // }
  // });

  fetch(
    "api.php?" +
      new URLSearchParams({
        target: "chapters",
        credentials: "base64encodedstr",
        id: 1,
      }).toString()
  );
}

async function selectChapter(index) {
  let chapterData = await fetchChapter(chapters[index].id);

  chapters[index].title = chapterData.title;
  chapters[index].story = chapterData.story;
  chapters[index].location = chapterData.location;
  chapters[index].date = chapterData.createdAt;

  selectedChapter.value = index;
}

async function addChapterAndEdit() {
  const data = {
    id: null,
    title: "Chapter title",
    date: new Date().toISOString().slice(0, 10),
    location: "",
    story: "",
  };

  const chapterData = await postChapter(data);
  data.id = chapterData.id;

  chapters.push(data);
  selectedChapter.value = chapters.length - 1;
}

async function updateChapter(chapter) {
  chapters[selectedChapter.value] = chapter;

  await putChapter(chapter);
}

onMounted(async () => {
  let chaptersData = await fetchAllChapters();
  chaptersData = chaptersData.map((chapter) => {
    return {
      id: chapter.id,
      title: chapter.title,
      date: chapter.createdAt,
      location: "",
      story: "",
    };
  });

  chapters.length = 0;
  chapters.push(...chaptersData);

  let userData = await fetchUser();

  console.log(userData);
});
</script>

<template>
  <v-app>
    <v-btn @click="fetchApi">Test fetch</v-btn>
    <edit-chapter
      v-if="selectedChapter !== null"
      :chapter="chapters[selectedChapter]"
      @close="selectedChapter = null"
      @update="updateChapter"
    ></edit-chapter>

    <template v-else>
      <chapters-header
        :is-chapters-empty="chapters.length == 0"
        @add-chapter="addChapterAndEdit"
      />
      <chapters :chapters="chapters" @select="selectChapter" />
    </template>
  </v-app>
</template>
